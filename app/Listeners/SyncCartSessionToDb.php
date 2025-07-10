<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Session;
use App\Models\CartItem;
use App\Models\Wishlist;
use App\Models\Product_details;

class SyncCartSessionToDb
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */


    public function handle(Login $event)
    {
        $user = $event->user;
        $sessionCart = Session::get('cart', []);
        $sessionWishlist = Session::get('wishlist', []);

        // 🛒 Đồng bộ giỏ hàng
        foreach ($sessionCart as $item) {
            $detail = Product_details::find($item['product_detail_id']);
            if (!$detail) continue;

            $existing = CartItem::where('user_id', $user->id)
                ->where('product_detail_id', $detail->id)
                ->first();

            if ($existing) {
                $existing->quantity += $item['quantity'];
                $existing->save();
            } else {
                CartItem::create([
                    'user_id'           => $user->id,
                    'product_detail_id' => $detail->id,
                    'quantity'          => $item['quantity'],
                    'price'             => $item['price'],
                ]);
            }
        }

        // ❤️ Đồng bộ danh sách yêu thích
        foreach ($sessionWishlist as $row) {
            $detailId = $row['product_detail_id'] ?? null;
            if (!$detailId) continue;

            $detail = Product_details::find($detailId);
            if (!$detail) continue;

            $existing = Wishlist::where('user_id', $user->id)
                ->where('product_detail_id', $detailId)
                ->first();

            if ($existing) {
                $existing->quantity += $row['quantity'] ?? 1;
                $existing->save();
            } else {
                Wishlist::create([
                    'user_id'           => $user->id,
                    'product_detail_id' => $detailId,
                    'quantity'          => $row['quantity'] ?? 1,
                ]);
            }
        }

        // 🧹 Xoá session sau khi sync
        Session::forget('cart');
        Session::forget('wishlist');
    }
}
