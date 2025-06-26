<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Session;


class CheckoutController extends Controller
{
    public function show()
    {

        $cart = session('cart', []);
        return view('user.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $method = $request->payment_method;

        if ($method === 'momo') {
            // Gắn logic Momo nếu có
            return redirect()->to('https://momo.vn');
        }

        if ($method === 'vnpay') {
            $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            $shipping = 0;
            $discount = 0;
            $total = $subtotal - $discount + $shipping;

            // Lưu tạm dữ liệu vào session để dùng lại sau khi thanh toán thành công
            session()->put('order_data', [
                'cart' => $cart,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'discount' => $discount,
                'total' => $total,
                'customer' => $request->only('name', 'phone', 'address', 'email', 'note', 'coupon_code'),
            ]);

            // Redirect sang VNPAY
            return redirect()->route('vnpay.payment');
        }


        // 👉 Nếu là COD: lưu đơn
        try {
            DB::beginTransaction();

            $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            $shipping = 0;
            $discount = 0;
            $total = $subtotal - $discount + $shipping;

            $order = Order::create([
                'id_user' => Auth::id() ?? 'guest',
                'id_payment' => 'PMT' . time(),
                'id_shipping' => 'SHIP' . time(),
                'order_date' => now(),
                'suptotal' => $subtotal,
                'payment_method' => 'Tiền Mặt',
                'payment_status' => 'Chưa Thanh Toán',
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
                'note' => $request->note,
                'coupon_code' => $request->coupon_code,
                'coupon_discount' => $discount,
                'shipping_fee' => $shipping,
                'total' => $total,
                'status' => 'Chờ Xác Nhận',
            ]);

            foreach ($cart as $detailId => $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_detail_id' => $detailId,
                    'product_name' => $item['product_name'],
                    'size' => $item['size'],
                    'color' => $item['color'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('checkout')->with('success', '🎉 Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi đặt hàng: ' . $e->getMessage());
        }
    }
}
