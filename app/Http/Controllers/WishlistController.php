<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;
use App\Models\Product_details;

class WishlistController extends Controller
{
    public function index()
    {
        $session = session()->get('wishlist', []);
        // $session là mảng [ detail_id => ['product_detail_id'=>…, 'quantity'=>…], … ]

        $items = collect($session)
            ->map(function ($row) {
                $detail = Product_details::with('product')->find($row['product_detail_id']);
                if (!$detail) return null;
                // Gắn thêm property quantity để view dễ dùng
                $detail->wishlist_quantity = $row['quantity'];
                return $detail;
            })
            ->filter()  // loại bỏ null nếu detail không tìm thấy
            ->values(); // làm lại index 0,1,2…

        return view('user.wishlist', compact('items'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
        ]);

        $detailId = $request->product_detail_id;
        $wishlist = session()->get('wishlist', []);

        // Kiểm tra sản phẩm đã tồn tại trong wishlist chưa
        if (array_key_exists($detailId, $wishlist)) {
            $message = 'Sản phẩm đã có trong danh sách yêu thích.';
            return $request->ajax()
                ? response()->json(['message' => $message], 409)
                : back()->with('info', $message);
        }

        // Thêm sản phẩm vào wishlist
        $wishlist[$detailId] = [
            'product_detail_id' => $detailId,
            'quantity'          => 1,
        ];
        session()->put('wishlist', $wishlist);

        $message = 'Đã thêm vào danh sách yêu thích!';
        return $request->ajax()
            ? response()->json(['message' => $message])
            : back()->with('success', $message);
    }


    public function moveToCart(Request $request)
    {
        $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
        ]);

        $detail = Product_details::with('product')->find($request->product_detail_id);

        if (!$detail) {
            return back()->with('error', 'Không tìm thấy sản phẩm chi tiết.');
        }

        // Thêm vào giỏ
        $cart = session()->get('cart', []);
        $key = $detail->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += 1;
        } else {
            $cart[$key] = [
                'product_detail_id' => $detail->id,
                'product_name'      => $detail->product->name,
                'size'              => $detail->size,
                'color'             => $detail->color,
                'price'             => $detail->price,
                'quantity'          => 1,
                'image'             => $detail->image,
            ];
        }

        session()->put('cart', $cart);

        // Xóa khỏi wishlist nếu tồn tại

        // Xóa khỏi wishlist theo ID gốc đã lưu
        $originalId = $request->input('original_product_detail_id');
        $wishlist = session()->get('wishlist', []);
        unset($wishlist[$originalId]);
        session()->put('wishlist', $wishlist);


        return back()->with('success', 'Đã thêm vào giỏ hàng.');
    }


    public function remove($id)
    {
        $wishlist = session()->get('wishlist', []);
        if (isset($wishlist[$id])) {
            unset($wishlist[$id]);
            session()->put('wishlist', $wishlist);
            return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích.');
        }
        return back()->with('error', 'Sản phẩm không có trong danh sách yêu thích.');
    }
    public function clear()
    {
        session()->forget('wishlist');
        return back()->with('success', 'Đã xóa toàn bộ danh sách yêu thích.');
    }
}
