<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show()
    {

        $cart = session('cart', []);
        return view('user.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $method = $request->payment_method;

        if ($method === 'momo') {
            // Redirect sang cổng thanh toán Momo (nếu tích hợp)
            return redirect()->to('https://momo.vn'); // chỉ là ví dụ
        }

        if ($method === 'vnpay') {
            // Redirect sang VNPAY
            return redirect()->to('https://vnpay.vn'); // ví dụ
        }

        // Nếu là COD (thu hộ), lưu đơn luôn
        // Order::create([
        //     // thông tin đơn hàng...
        //     'payment_method' => 'COD',
        //     'status' => 'Đang xử lý',
        // ]);

        session()->forget('cart');
        return redirect()->route('checkout')->with('success', 'Đặt hàng thành công!');
    }
}
