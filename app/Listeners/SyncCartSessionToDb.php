<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Session;
use App\Models\CartItem;
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
                    'price'             => $item['price'], // Optional
                ]);
            }
        }

        // Optional: Xóa giỏ session sau khi đồng bộ
        Session::forget('cart');
    }
}
