<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;
use App\Models\Product_details;
use Auth;
use App\Models\Wishlist;
use App\Models\CartItem;

class WishlistController extends Controller
{
    public function index()
    {

        if (Auth::check()) {
            $items = Wishlist::with('productdetail.product')
                ->where('user_id', Auth::id())
                ->get()
                ->map(function ($item) {
                    $item->productdetail->wishlist_quantity = $item->quantity;
                    return $item->productdetail;
                });
            return view('user.wishlist', compact('items'));
        }
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
        $message = 'Đã thêm vào danh sách yêu thích!';

        if (Auth::check()) {
            $exists = Wishlist::where('user_id', Auth::id())
                ->where('product_detail_id', $detailId)
                ->exists();

            if ($exists) {
                $message = 'Sản phẩm đã có trong danh sách yêu thích.';
                return $request->ajax()
                    ? response()->json(['message' => $message], 409)
                    : back()->with('info', $message);
            }

            Wishlist::create([
                'user_id'           => Auth::id(),
                'product_detail_id' => $detailId,
                'quantity'          => 1,
            ]);

            return $request->ajax()
                ? response()->json(['message' => $message])
                : back()->with('success', $message);
        }

        // Nếu chưa đăng nhập → lưu vào session
        $wishlist = session('wishlist', []);

        if (array_key_exists($detailId, $wishlist)) {
            $message = 'Sản phẩm đã có trong danh sách yêu thích.';
            return $request->ajax()
                ? response()->json(['message' => $message], 409)
                : back()->with('info', $message);
        }

        $wishlist[$detailId] = [
            'product_detail_id' => $detailId,
            'quantity'          => 1,
        ];

        session()->put('wishlist', $wishlist);

        return $request->ajax()
            ? response()->json(['message' => $message])
            : back()->with('success', $message);
    }

    public function moveToCart(Request $request)
    {
        $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
        ]);

        $detailId = $request->product_detail_id;
        $quantity = $request->input('quantity', 1); // 👈 mặc định là 1

        $detail = Product_details::with('product')->find($detailId);
        if (!$detail) {
            return back()->with('error', 'Không tìm thấy sản phẩm chi tiết.');
        }

        if (Auth::check()) {
            $userId = Auth::id();

            // Thêm vào giỏ DB
            $existing = CartItem::where('user_id', $userId)
                ->where('product_detail_id', $detailId)
                ->first();

            if ($existing) {
                $existing->quantity += $quantity;
                $existing->save();
            } else {
                CartItem::create([
                    'user_id'           => $userId,
                    'product_detail_id' => $detailId,
                    'quantity'          => $quantity,
                    'price'             => $detail->price,
                ]);
            }

            dd(
                Wishlist::where('user_id', $userId)
                    ->where('product_detail_id', $detailId)
                    ->first()
            );
        }

        // Nếu chưa đăng nhập → dùng session
        $cart = session('cart', []);
        $key  = $detailId;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_detail_id' => $detail->id,
                'product_name'      => $detail->product->name,
                'size'              => $detail->size,
                'color'             => $detail->color,
                'price'             => $detail->price,
                'quantity'          => $quantity,
                'image'             => $detail->image,
            ];
        }

        session()->put('cart', $cart);

        // Xoá khỏi wishlist session nếu tồn tại
        $wishlist = session('wishlist', []);
        unset($wishlist[$detailId]);
        session()->put('wishlist', $wishlist);

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
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
