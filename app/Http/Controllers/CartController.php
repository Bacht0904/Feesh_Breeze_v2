<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_details;
use Illuminate\Support\Facades\Auth;

use App\Models\CartItem;
use App\Models\Coupon; // Đổi tên model cho chuẩn (không _)

class CartController extends Controller
{
    // 👉 Hiển thị giỏ hàng
    public function cart()
    {
        // Nếu user đã đăng nhập, hiển thị giỏ từ DB
        if (Auth::check()) {
            $items = CartItem::with('productdetail')
                ->where('user_id', Auth::id())
                ->get();

            return view('user.cart', compact('items'));
        }

        // Nếu chưa đăng nhập, hiển thị từ session
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

        $detail = Product_details::with('product')->findOrFail($request->product_detail_id);
        $quantity = $request->quantity;

        // 📦 Nếu user đã đăng nhập → lưu vào DB
        if (Auth::check()) {
            $userId = Auth::id();

            // Kiểm tra tồn tại trong DB
            $existing = CartItem::where('user_id', $userId)
                ->where('product_detail_id', $detail->id)
                ->first();

            if ($existing) {
                // Cộng thêm số lượng nếu đã có
                $existing->quantity += $quantity;
                $existing->save();
            } else {
                // Tạo mới nếu chưa có
                CartItem::create([
                    'user_id'           => $userId,
                    'product_detail_id' => $detail->id,
                    'quantity'          => $quantity,
                    'price'             => $detail->price,
                ]);
            }

            return $request->ajax()
                ? response()->json(['message' => 'Đã thêm vào giỏ hàng DB!'])
                : back()->with('success', 'Đã thêm vào giỏ hàng!');
        }

        // 🛒 Nếu chưa đăng nhập → lưu session như cũ
        $cart = session('cart', []);

        $size  = $detail->size ?? 'default';
        $color = $detail->color ?? 'default';
        $key   = "{$detail->id}-{$size}-{$color}";

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_detail_id' => $detail->id,
                'product_name'      => $detail->product->name,
                'size'              => $size,
                'color'             => $color,
                'price'             => $detail->price,
                'quantity'          => $quantity,
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
        if (Auth::check()) {
            $item = CartItem::where('id', $key)->where('user_id', Auth::id())->first();
            if ($item) {
                $item->delete();
                return back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng (DB)');
            }
        }

        // Xử lý xoá session như cũ
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng.');
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'quantities' => 'required|array',
            'product_detail_ids' => 'required|array',
        ]);

        $outOfStockItems = [];

        if (Auth::check()) {
            // 👉 Với user đã đăng nhập → update bảng cart_items
            foreach ($validated['quantities'] as $cartItemId => $qty) {
                $newDetailId = $validated['product_detail_ids'][$cartItemId] ?? null;
                $qty = max(1, (int) $qty);

                $cartItem = CartItem::where('id', $cartItemId)
                    ->where('user_id', Auth::id())
                    ->first();
                if (!$cartItem) continue;

                $detail = Product_details::with('product')->find($newDetailId);
                if (!$detail) continue;

                $max = $detail->quantity;
                if ($qty > $max) {
                    $outOfStockItems[] = "{$detail->product->name} (còn tối đa: $max)";
                    if ($max < 1) continue;
                    $qty = $max;
                }

                // Nếu user đã chọn biến thể mới
                if ($newDetailId != $cartItem->product_detail_id) {
                    // Kiểm tra xem sản phẩm biến thể mới đã có trong giỏ hay chưa
                    $existing = CartItem::where('user_id', Auth::id())
                        ->where('product_detail_id', $newDetailId)
                        ->first();

                    if ($existing) {
                        // Gộp số lượng và xoá dòng cũ
                        $existing->quantity += $qty;
                        $existing->save();
                        $cartItem->delete();
                    } else {
                        $cartItem->product_detail_id = $newDetailId;
                        $cartItem->price = $detail->price;
                        $cartItem->quantity = $qty;
                        $cartItem->save();
                    }
                } else {
                    // Không thay đổi biến thể → chỉ cập nhật số lượng
                    $cartItem->quantity = $qty;
                    $cartItem->save();
                }
            }

            return redirect()->route('cart')->with(
                $outOfStockItems ? 'warning' : 'success',
                $outOfStockItems
                    ? 'Một vài sản phẩm đã tự động điều chỉnh: ' . implode(', ', $outOfStockItems)
                    : '🛒 Giỏ hàng đã được cập nhật!'
            );
        }

        // 🛒 Với guest → xử lý session như cũ
        $newCart = [];
        foreach ($validated['quantities'] as $key => $qty) {
            $detailId = $validated['product_detail_ids'][$key] ?? null;
            $qty = max(1, (int) $qty);

            $detail = Product_details::with('product')->find($detailId);
            if (!$detail) continue;

            $max = $detail->quantity;
            if ($qty > $max) {
                $outOfStockItems[] = "{$detail->product->name} (tối đa: $max)";
                if ($max < 1) continue;
                $qty = $max;
            }

            $size  = $detail->size ?? 'default';
            $color = $detail->color ?? 'default';
            $keyName = "{$detail->id}-{$size}-{$color}";

            $newCart[$keyName] = [
                'product_id'         => $detail->product_id,
                'product_detail_id'  => $detail->id,
                'product_name'       => $detail->product->name,
                'size'               => $size,
                'color'              => $color,
                'price'              => $detail->price,
                'quantity'           => $qty,
                'image'              => $detail->image,
            ];
        }

        session()->put('cart', $newCart);

        return redirect()->route('cart')->with(
            $outOfStockItems ? 'warning' : 'success',
            $outOfStockItems
                ? 'Một vài sản phẩm đã được tự động điều chỉnh: ' . implode(', ', $outOfStockItems)
                : '🛒 Giỏ hàng đã được cập nhật!'
        );
    }


    // 👉 Xóa sạch giỏ hàng
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Đã làm sạch giỏ hàng.');
    }
}
