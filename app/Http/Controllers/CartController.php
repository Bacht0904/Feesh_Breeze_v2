<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_details; // Đổi tên model cho chuẩn (không _)

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
        $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
            'quantity'          => 'required|integer|min:1',
            'size'              => 'required|string|max:50',
        ]);

        $detail = Product_details::findOrFail($request->product_detail_id);
        $cart   = session()->get('cart', []);

        $key = "{$detail->id}-{$detail->size}-{$detail->color}";

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                'product_detail_id' => $detail->id,
                'product_name'      => $detail->product->name,
                'size'              => $detail->size,
                'color'             => $detail->color,
                'price'             => $detail->price,
                'quantity'          => $request->quantity,
                'image'             => $detail->image,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }


    // 👉 Xóa sản phẩm khỏi giỏ hàng
    public function remove($key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    // 👉 Cập nhật số lượng
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        foreach ($request->quantities as $key => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) {
                unset($cart[$key]);
            } elseif (isset($cart[$key])) {
                $cart[$key]['quantity'] = $qty;
            }
        }
        session()->put('cart', $cart);
        return redirect()->route('cart')->with('success', 'Giỏ hàng đã được cập nhật!');
    }

    // 👉 Xóa sạch giỏ hàng
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Đã làm sạch giỏ hàng.');
    }
}
