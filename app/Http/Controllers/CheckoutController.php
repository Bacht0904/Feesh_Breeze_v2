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
use App\Models\CartItem;
use App\Models\Product_details;
use App\Models\User;
use Illuminate\Support\Collection;



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
        if (Auth::check()) {
            // 1. Lấy cart từ database, với quan hệ productdetail → product
            $cartItems = CartItem::with('productdetail.product')
                ->where('user_id', Auth::id())
                ->get();

            $empty = $cartItems->isEmpty();

            // 2. Chuyển thành mảng giống session
            $cart = $cartItems->map(fn($item) => [
                'product_name' => optional(optional($item->productdetail)->product)->name
                    ?? 'Sản phẩm đã xoá',
                'size'         => $item->productdetail->size ?? '-',
                'price'        => $item->productdetail->price ?? 0,
                'image'        => $item->productdetail->image ?? 'img/default.png',
                'quantity'     => $item->quantity,
            ])->toArray();
        } else {
            $cart  = session('cart', []);
            $empty = empty($cart);
        }

        // 3. Coupon, address và tính tổng
        $availableCoupons = Coupon::where('status', 'active')->get();
        $address          = Auth::user()->address ?? '';
        $totals           = $this->calculateTotals($cart, $address);

        // 4. Đưa hết về view
        return view('user.checkout', array_merge(
            compact('cart', 'empty', 'availableCoupons'),
            $totals
        ));
    }




      public function applyCoupon(Request $request)
    {
        $code = $request->input('coupon_code');
        $coupon = Coupon::where('code', $code)
            ->where('status', 'active')
            ->where('quantity', '>', 0)
            ->first();

        if (!$coupon) {
            return back()->with(
                'voucher_message',
                'Mã giảm giá không hợp lệ hoặc đã hết lượt sử dụng.'
            );
        }

        // Giảm lượt dùng
        $coupon->decrement('quantity');
        if ($coupon->quantity <= 0) {
            $coupon->status = 'inactive';
            $coupon->save();
        }

        // 📦 Lấy giỏ hàng từ session hoặc database
        if (Auth::check()) {
            $cartItems = CartItem::with('productdetail')
                ->where('user_id', Auth::id())
                ->get();

            $total = $cartItems->sum(
                fn($item) =>
                ($item->productdetail->price ?? 0) * ($item->quantity ?? 0)
            );
        } else {
            $cart = session('cart', []);
            $total = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 0));
        }

        // 🔥 Tính giảm giá
        $discount = $coupon->type === 'percent'
            ? $total * ($coupon->value / 100)
            : $coupon->value;

        $discount = min($discount, $total); // Không được vượt quá tổng

        // 💾 Lưu vào session
        session([
            'coupon' => [
                'code' => $coupon->code,
                'discount' => $discount,
            ]
        ]);

        // 📘 (Tuỳ chọn) Lưu lịch sử áp dụng
        

        return back()->with(
            'voucher_message',
            "Áp dụng mã {$coupon->code} thành công! Giảm " . number_format($discount) . ' đ'
        );
    }


    public function process(Request $request)
    {
        // 1. Validate input
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:500',
            'payment_method' => 'required|in:cod,momo',
            'note'           => 'nullable|string|max:1000',
            'coupon_code'    => 'nullable|string|exists:coupons,code',
        ]);

        // 2. Build full cart array giống như show()
        if (Auth::check()) {
            $cartItems = CartItem::with('productdetail.product')
                ->where('user_id', Auth::id())
                ->get();

            $items = $cartItems->map(function ($item) {
                $d = $item->productdetail;
                return [
                    'product_detail_id' => $item->product_detail_id,
                    'product_name'      => optional(optional($d)->product)->name ?? 'Sản phẩm đã xoá',
                    'size'              => $d->size    ?? '-',
                    'color'             => $d->color   ?? null,
                    'price'             => $d->price   ?? 0,
                    'image'             => $d->image   ?? 'img/default.png',
                    'quantity'          => $item->quantity,
                ];
            })->toArray();
        } else {
            $items = session('cart', []);
        }

        if (empty($items)) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // 3. Tính totals
        $totals = $this->calculateTotals($items, $data['address']);

        // 4. Chuyển đến handler tương ứng
        return match ($data['payment_method']) {
            'cod'  => $this->handleCashOnDelivery($request, $items, $totals),
            'momo' => $this->handleMomo($request, $items, $totals),
            default => back()->with('error', 'Phương thức thanh toán không hợp lệ!'),
        };
    }
    protected function calculateTotals(array|Collection $cart, ?string $address = null): array
    {
        // 🔢 Tính tổng tiền hàng
        $subtotal = collect($cart)->sum(function ($item) {
            if (is_array($item)) {
                return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
            }

            if (is_object($item)) {
                $price = optional($item->productDetail)->price ?? 0;
                $quantity = $item->quantity ?? 0;
                return $price * $quantity;
            }

            return 0;
        });

        // 🚚 Phí giao hàng mặc định
        $shipping = 30000;
        if ($address) {
            $normalized = mb_strtolower($address);
            if (str_contains($normalized, 'hồ chí minh') || str_contains($normalized, 'tp.hcm') || str_contains($normalized, 'hcm')) {
                $shipping = 0;
            }
        }

        // 🎟 Áp dụng mã giảm giá
        $discount = 0;
        $couponData = session('coupon');
        if (is_array($couponData) && isset($couponData['discount'])) {
            $discount = min($couponData['discount'], $subtotal);
        }

        // 🧾 Tổng cộng
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
                    'coupon_code'     => $request->coupon_code->code ?? null,
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
                if (Auth::check()) {
                    CartItem::where('user_id', Auth::id())->delete();
                }

                // XÓA luôn coupon & order_data (nếu trả về từ MOMO)
                session()->forget(['coupon', 'order_data']);

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
        $orderData = session('order_data', []);
        $rawCart   = $orderData['cart'] ?? session('cart', []);
        $empty     = empty($rawCart);

        // Nếu MoMo xác nhận thanh toán thành công
        if ((int)$request->resultCode === 0 && !$empty) {
            try {
                $saved = DB::transaction(function () use ($orderData, $rawCart) {
                    $order = $orderData;
                    $cart  = $rawCart;

                    // Tạo đơn hàng
                    $orderModel = Order::create([
                        'id_user'         => Auth::id(),
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

                    foreach ($cart as $item) {
                        $detail = Product_details::find($item['product_detail_id']);
                        OrderDetail::create([
                            'order_id'          => $orderModel->id,
                            'product_detail_id' => $item['product_detail_id'],
                            'product_name'      => optional($detail->product)->name ?? 'Sản phẩm đã xoá',
                            'size'              => $detail->size ?? '-',
                            'color'             => $item['color'] ?? '-',
                            'price'             => $detail->price ?? 0,
                            'image'             => $detail->image ?? 'img/default.png',
                            'quantity'          => $item['quantity'] ?? 1,
                        ]);
                    }

                    // Xoá giỏ
                    session()->forget(['cart', 'coupon', 'order_data']);
                    if (Auth::check()) {
                        CartItem::where('user_id', Auth::id())->delete();
                    }

                    return $orderModel;
                });

                // Thông báo admin
                $recipients = User::whereIn('role', ['admin', 'staff'])->get();
                Notification::send($recipients, new OrderPlaced($saved));

                return redirect()->route('user.checkoutsuccess', ['id' => $saved->id]);
            } catch (\Throwable $e) {
                return back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
            }
        }

        // Nếu lỗi hoặc bị hủy
        $cart = collect($rawCart)->map(function ($item) {
            $detail = Product_details::find($item['product_detail_id']);
            return [
                'product_name' => optional($detail->product)->name ?? 'Sản phẩm đã xoá',
                'size'         => $detail->size ?? '-',
                'price'        => $detail->price ?? 0,
                'image'        => $detail->image ?? 'img/default.png',
                'quantity'     => $item['quantity'] ?? 0,
            ];
        })->toArray();

        $availableCoupons = Coupon::where('status', 'active')->get();
        $address = $orderData['customer']['address'] ?? Auth::user()->address ?? '';
        $totals  = $this->calculateTotals($cart, $address);

        return view('user.checkout', array_merge(
            compact('cart', 'empty', 'availableCoupons', 'address'),
            $totals
        ))->with('error', 'Xác thực không thành công hoặc đã bị hủy.');
    }

    public function handleMomoCancel()
    {
        // Xóa dữ liệu đơn hàng khỏi session

        session()->forget('order_data');
        return redirect('/')->with('error', 'Thanh toán đã bị hủy.');
    }
}
