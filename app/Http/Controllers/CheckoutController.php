<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Coupon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderPlaced;
use App\Models\User;
// Đổi tên model cho chuẩn (không _)

class CheckoutController extends Controller
{
    public function success($id)
    {
        $order = Order::with('orderDetails')->findOrFail($id);

        // (Tuỳ chọn bảo vệ user)


        return view('user.checkoutsuccess', compact('order'));
    }

    public function show()
    {
        $cart = session('cart', []);
        $empty = empty($cart);
        $availableCoupons = Coupon::where('status', 'active')

            ->get();

        $address = Auth::user()->address ?? '';
        $totals = $this->calculateTotals($cart, $address);
        if ($empty) {
            return view('user.checkout', array_merge(
                compact('cart', 'empty', 'availableCoupons'),
                $totals
            ));
        }

        return view('user.checkout', array_merge(compact('cart', 'empty', 'availableCoupons'), $totals));
    }



    public function applyCoupon(Request $request)
    {
        $code = $request->input('coupon_code');

        $coupon = Coupon::where('code', $code)
            ->where('status', 'active')
            ->first();

        if (!$coupon) {
            return back()->with('voucher_message', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        $cart = session('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        if ($coupon->type === 'percent') {
            $discount = $total * ($coupon->value / 100);
        } elseif ($coupon->type === 'fixed') {
            $discount = $coupon->value;
        }

        $discount = min($discount, $total); // không giảm vượt quá tổng

        session(['coupon' => [
            'code' => $coupon->code,
            'discount' => $discount,
        ]]);

        return back()->with('voucher_message', "Áp dụng mã {$coupon->code} thành công! Giảm " . number_format($discount, 0) . ' đ');
    }

    public function process(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $method = $request->payment_method;

        $totals = $this->calculateTotals($cart, $request->address);


        return match ($method) {
            'momo' => $this->handleMomo($request, $cart, $totals),
            'cod' => $this->handleCashOnDelivery($request, $cart, $totals),
            default => back()->with('error', 'Phương thức thanh toán không hợp lệ!'),
        };
    }

    protected function calculateTotals(array $cart, ?string $address = null): array
    {
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // 🚚 Mặc định phí giao hàng
        $shipping = 30000;

        // ⛳ Xử lý miễn phí nếu địa chỉ ở TP HCM
        if ($address) {
            $normalized = strtolower($address);
            if (str_contains($normalized, 'hồ chí minh') || str_contains($normalized, 'tp.hcm') || str_contains($normalized, 'hcm')) {
                $shipping = 0;
            }
        }

        $discount = 0;
        $couponData = session('coupon');
        if ($couponData) {
            $discount = min($couponData['discount'], $subtotal);
        }

        $total = max($subtotal + $shipping - $discount, 0);

        return compact('subtotal', 'shipping', 'discount', 'total');
    }




    protected function handleCashOnDelivery(Request $request, array $cart, array $totals)
    {
        try {
            $order = DB::transaction(function () use ($request, $cart, $totals) {
                // 1. Tạo đơn hàng
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

                // 2. Lưu chi tiết đơn hàng
                foreach ($cart as $item) {
                    $order->orderDetails()->create([
                        'product_detail_id' => $item['product_detail_id'],
                        'product_name'      => $item['product_name'],
                        'size'              => $item['size'],
                        'color'             => $item['color'],
                        'price'             => $item['price'],
                        'image'             => $item['image'] ?? null,
                        'quantity'          => $item['quantity'],
                    ]);
                }

                // 3. Xoá giỏ hàng
                session()->forget('cart');

                return $order; // ✅ Trả đối tượng Order ra ngoài
            });
            $recipients = User::whereIn('role', ['admin', 'staff'])->get();
            if ($recipients->isNotEmpty()) {

                Notification::send($recipients, new OrderPlaced($order));
            }
            $recipients->each(function ($user) {
                logger('🔔 Notifying user: ' . $user->name . ' | ' . $user->email);
            });



            // 4. Redirect đến trang cảm ơn
            return redirect()->route('user.checkoutsuccess', ['id' => $order->id]);
        } catch (\Throwable $e) {
            dd($e);
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
                $saved = DB::transaction(function () use ($order) {
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
                            'image'             => $item['image'] ?? null,
                            'quantity'          => $item['quantity'],
                        ]);
                    }

                    session()->forget(['cart', 'order_data']);
                    return $saved;
                });
                $recipients = User::whereIn('role', ['admin', 'staff'])->get();
                if ($recipients->isNotEmpty()) {
                    Notification::send($recipients, new OrderPlaced($order));
                }

                return redirect()->route('user.checkoutsuccess', ['id' => $saved->id]);
            } catch (\Throwable $e) {
                
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
