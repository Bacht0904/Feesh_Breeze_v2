<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_details;

class CartController extends Controller
{
    // 👉 Hiển thị giỏ hàng
    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('user.cart', compact('cart'));
    }

    // 👉 Thêm sản phẩm chi tiết vào giỏ hàng
    public function addDetail(Request $request)
    {
        $detail = Product_details::findOrFail($request->product_detail_id);
        $cart = session()->get('cart', []);
        $key = $detail->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                'product_name' => $detail->product->name,
                'size' => $detail->size,
                'color' => $detail->color,
                'price' => $detail->price,
                'image' => $detail->image,
                'quantity' => $request->quantity
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // 👉 Xoá sản phẩm khỏi giỏ hàng
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng.');
    }

    // 👉 Cập nhật số lượng các sản phẩm trong giỏ hàng
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        foreach ($request->quantities as $id => $qty) {
            $qty = (int) $qty;

            if ($qty <= 0) {
                unset($cart[$id]); // Xoá nếu số lượng không hợp lệ
            } elseif (isset($cart[$id])) {
                $cart[$id]['quantity'] = $qty; // Cập nhật số lượng
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Giỏ hàng đã được cập nhật!');
    }

    // 👉 Làm sạch toàn bộ giỏ hàng
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Đã làm sạch giỏ hàng.');
    }
}
