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
            // Đây là ID của bản ghi wishlist ban đầu
            'original_product_detail_id' => 'required|integer|exists:wishlists,product_detail_id',
            // Đây là ID variant mới bạn chọn để cho vào cart
            'product_detail_id'          => 'required|integer|exists:product_details,id',
        ]);

        $origId = $request->input('original_product_detail_id');
        $newId  = $request->input('product_detail_id');
        $qty    = $request->input('quantity', 1);

        // Lấy thông tin chi tiết mới (product variant)
        $detail = Product_details::with('product')->findOrFail($newId);

        if (Auth::check()) {
            // — DB-based cart
            $userId = Auth::id();

            // a) Thêm hoặc cập nhật CartItem
            $cartItem = CartItem::firstOrNew([
                'user_id'           => $userId,
                'product_detail_id' => $newId,
            ]);
            $cartItem->quantity = $cartItem->exists
                ? $cartItem->quantity + $qty
                : $qty;
            $cartItem->price    = $detail->price;
            $cartItem->save();

            // b) Xóa khỏi bảng wishlist
            Wishlist::where('user_id', $userId)
                ->where('product_detail_id', $origId)
                ->delete();
        } else {
            // — Session-based cart
            $cart     = session('cart', []);
            $wishlist = session('wishlist', []);

            // a) Thêm/cập nhật cart
            if (isset($cart[$newId])) {
                $cart[$newId]['quantity'] += $qty;
            } else {
                $cart[$newId] = [
                    'product_detail_id' => $detail->id,
                    'product_name'      => $detail->product->name,
                    'size'              => $detail->size,
                    'color'             => $detail->color,
                    'price'             => $detail->price,
                    'quantity'          => $qty,
                    'image'             => $detail->image,
                ];
            }
            session(['cart' => $cart]);

            // b) Xóa khỏi session wishlist
            if (isset($wishlist[$origId])) {
                unset($wishlist[$origId]);
                session(['wishlist' => $wishlist]);
            }
        }

        return back()->with('success', 'Đã chuyển sang giỏ và xóa khỏi yêu thích.');
    }

    /**
     * Xóa 1 sản phẩm khỏi wishlist
     */
    public function remove($detailId)
    {
        if (Auth::check()) {
            // DB-based
            Wishlist::where('user_id', Auth::id())
                ->where('product_detail_id', $detailId)
                ->delete();
        } else {
            // Session-based
            $wishlist = session('wishlist', []);
            if (isset($wishlist[$detailId])) {
                unset($wishlist[$detailId]);
                session(['wishlist' => $wishlist]);
            }
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích.');
    }
    public function clear()
    {
        session()->forget('wishlist');
        return back()->with('success', 'Đã xóa toàn bộ danh sách yêu thích.');
    }
}
