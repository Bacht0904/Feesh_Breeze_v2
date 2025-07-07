<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_details;
use App\Models\Coupon; // Đổi tên model cho chuẩn (không _)

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
        ]);

        $detail = Product_details::findOrFail($request->product_detail_id);
        $cart   = session('cart', []);

        // Tìm key đầu tiên có cùng product_detail_id (bất kể size, color)
        $existingKey = collect($cart)->search(fn($item) => $item['product_detail_id'] == $detail->id);

        if ($existingKey !== false) {
            $cart[$existingKey]['quantity'] += $request->quantity;
        } else {
            // Tạo key mới theo chuẩn
            $size  = $detail->size ?? 'default';
            $color = $detail->color ?? 'default';
            $key   = "{$detail->id}-{$size}-{$color}";

            $cart[$key] = [
                'product_detail_id' => $detail->id,
                'product_name'      => $detail->product->name,
                'size'              => $size,
                'color'             => $color,
                'price'             => $detail->price,
                'quantity'          => $request->quantity,
                'image'             => $detail->image,
            ];
        }

        session()->put('cart', $cart);

        return $request->ajax()
            ? response()->json([
                'message'   => 'Đã thêm vào giỏ hàng!',
                'cartCount' => array_sum(array_column($cart, 'quantity')),
            ])
            : back()->with('success', 'Đã thêm vào giỏ hàng!');
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
        $newCart = [];

        foreach ($request->quantities as $oldKey => $qty) {
            $qty = max(1, (int) $qty);

            // Lấy product_detail_id mới mà user chọn
            $newDetailId = $request->input("product_detail_ids.$oldKey");
            $detail = Product_details::with('product')->find($newDetailId);

            if (!$detail) continue;

            // Ghi lại bằng key mới (đổi biến thể => key đổi)
            $newCart[$newDetailId] = [
                'product_id'         => $detail->product_id,
                'product_detail_id'  => $detail->id,
                'product_name'       => $detail->product->name,
                'size'               => $detail->size,
                'color'              => $detail->color,
                'price'              => $detail->price,
                'quantity'           => $qty,
                'image'              => $detail->image,
            ];
        }

        session()->put('cart', $newCart);

        return redirect()->route('cart')->with('success', '🛒 Giỏ hàng đã được cập nhật!');
    }




    // 👉 Xóa sạch giỏ hàng
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Đã làm sạch giỏ hàng.');
    }
}
