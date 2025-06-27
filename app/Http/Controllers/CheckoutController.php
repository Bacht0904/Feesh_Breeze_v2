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
            'momo' => redirect()->to('https://momo.vn'),
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
                    'email'           => $request->email,
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
}
