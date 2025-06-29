<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = session('cart', []);
        $empty = empty($cart);

        return view('user.checkout', compact('cart', 'empty'));
    }

    public function process(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $method = $request->payment_method;

        $totals = $this->calculateTotals($cart);

        return match ($method) {
            'momo' => $this->handleMomo($request, $cart, $totals),
            'vnpay' => $this->handleVnPay($request, $cart, $totals),
            'cod' => $this->handleCashOnDelivery($request, $cart, $totals),
            default => back()->with('error', 'Phương thức thanh toán không hợp lệ!'),
        };
    }

    protected function calculateTotals(array $cart): array
    {
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shipping = 0;
        $discount = 0;
        $total = $subtotal + $shipping - $discount;

        return compact('subtotal', 'shipping', 'discount', 'total');
    }

    protected function handleVnPay(Request $request, array $cart, array $totals)
    {
        session()->put('order_data', [
            'cart' => $cart,
            ...$totals,
            'customer' => $request->only('name', 'phone', 'address', 'email', 'note', 'coupon_code'),
        ]);

        return redirect()->route('vnpay.payment');
    }

    protected function handleCashOnDelivery(Request $request, array $cart, array $totals)
    {

        try {
            DB::transaction(function () use ($request, $cart, $totals) {
                $order = Order::create([
                    'id_user'         => Auth::id() ?? 'guest',
                    'id_payment'      => 'PMT' . now()->timestamp,
                    'id_shipping'     => 'SHIP' . now()->timestamp,
                    'order_date'      => now(),
                    'suptotal'        => $totals['subtotal'],
                    'payment_method'  => 'Tiền Mặt',
                    'payment_status'  => 'Chưa Thanh Toán',
                    'name'            => $request->name,
                    'phone'           => $request->phone,
                    'address'         => $request->address,
                    'email'           => $request->email ?? null,
                    'note'            => $request->note,
                    'coupon_code'     => $request->coupon_code,
                    'coupon_discount' => $totals['discount'],
                    'shipping_fee'    => $totals['shipping'],
                    'total'           => $totals['total'],
                    'status'          => 'Chờ Xác Nhận',
                ]);

                foreach ($cart as $item) {
                    OrderDetail::create([
                        'order_id'          => $order->id,
                        'product_detail_id' => $item['product_detail_id'],
                        'product_name'      => $item['product_name'],
                        'size'              => $item['size'],
                        'color'             => $item['color'],
                        'price'             => $item['price'],
                        'quantity'          => $item['quantity'],
                    ]);
                }

                session()->forget('cart');
            });

            return redirect()->route('checkout')->with('success', '🎉 Đặt hàng thành công!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }

    public function handleMomo(Request $request, array $cart, array $totals)
    {
        session()->put('order_data', [
            'cart'     => $cart,
            ...$totals,
            'customer' => $request->only('name', 'phone', 'address', 'email', 'note', 'coupon_code'),
        ]);

        $endpoint    = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey   = 'klm05TvNBzhg7h7j';
        $secretKey   = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderId     = (string) time();
        $requestId   = uniqid();
        $amount      = number_format($totals['total'], 0, '', '');
        $orderInfo   = "Thanh toán đơn hàng MoMo";
        $redirectUrl = route('momo.callback');
        $ipnUrl      = route('momo.callback');
        $extraData   = '';
        $requestType = "captureWallet";

        // Tạo chữ ký (signature theo định dạng JSON API v2)
        $rawData = [
            'partnerCode' => $partnerCode,
            'partnerName' => "MoMoTest",
            'storeId'     => "MoMoTestStore",
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType
        ];
        ksort($rawData);
        $rawHash    = urldecode(http_build_query($rawData));
        $rawSignature = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawSignature, $secretKey);


        $rawData['signature'] = $signature;

        // Gửi yêu cầu
        $response = $this->execPostRequest($endpoint, json_encode($rawData));
        $json     = json_decode($response, true);

        if (isset($json['payUrl'])) {
            return redirect()->away($json['payUrl']);
        }

        return back()->with('error', 'Không tạo được liên kết thanh toán MoMo.');
    }


    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Content-Length: ' . strlen($data)]
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function handleMomoCallback(Request $request)
    {

        $order = session('order_data');

        if (!$order) {
            return redirect()->route('user.checkout')->with('error', 'Giao dịch đã bị hủy hoặc thất bại.');
        }

        // Kiểm tra mã phản hồi từ MoMo
        if ($request->resultCode == 0) {
            // Debug thông tin trả về từ MoMo
            try {
                DB::transaction(function () use ($order) {
                    $saved = Order::create([
                        'id_user'         => Auth::id() ?? null,
                        'id_payment'      => 'PMT' . time(),
                        'id_shipping'     => 'SHIP' . time(),
                        'order_date'      => now(),
                        'suptotal'        => $order['subtotal'],
                        'payment_method'  => 'MOMO',
                        'payment_status'  => 'Đã Thanh Toán',
                        'name'            => $order['customer']['name'],
                        'phone'           => $order['customer']['phone'],
                        'address'         => $order['customer']['address'],
                        'email'           => $order['customer']['email'] ?? null,
                        'note'            => $order['customer']['note'],
                        'coupon_code'     => $order['customer']['coupon_code'],
                        'coupon_discount' => $order['discount'],
                        'shipping_fee'    => $order['shipping'],
                        'total'           => $order['total'],
                        'status'          => 'Chờ Xác Nhận',
                    ]);

                    foreach ($order['cart'] as $item) {
                        OrderDetail::create([
                            'order_id'          => $saved->id,
                            'product_detail_id' => $item['product_detail_id'],
                            'product_name'      => $item['product_name'],
                            'size'              => $item['size'],
                            'color'             => $item['color'],
                            'price'             => $item['price'],
                            'quantity'          => $item['quantity'],
                        ]);
                    }

                    session()->forget(['cart', 'order_data']);
                });


                return redirect()->route('checkout')->with('success', '🎉 Đặt hàng thành công!');
            } catch (\Throwable $e) {
                dd($e);
                return back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
            }
        }

        return view('user.checkout')->with('error', 'Xác thực không thành công hoặc bị hủy.');
    }
    public function handleMomoCancel()
    {
        // Xóa dữ liệu đơn hàng khỏi session        

        session()->forget('order_data');
        return redirect('/')->with('error', 'Thanh toán đã bị hủy.');
    }
}
