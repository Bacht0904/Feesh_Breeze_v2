<?php

namespace App\Providers;

use App\Models\Contact;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Auth\Events\Login;
use App\Listeners\SyncCartSessionToDb;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

use App\Models\CartItem;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }


    protected $listen = [
        Login::class => [
            SyncCartSessionToDb::class,
        ],
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            // Giỏ hàng

            if (Auth::check()) {
                $cartItemCount = CartItem::where('user_id', Auth::id())->sum('quantity');
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            } else {
                $cart = session('cart', []);
                $cartItemCount = collect($cart)->sum('quantity');

                // Đếm wishlist trong session
                $wishlistSession = session('wishlist', []);
                $wishlistCount = collect($wishlistSession)->sum('quantity');
            }
            // Số lượng liên hệ
            $contactCount = Contact::count();

            // Danh sách loại sản phẩm
            $categories = Category::all();
            // Gắn vào view
            $view->with([
                'cartItemCount' => $cartItemCount,
                'wishlistCount' => $wishlistCount,
                'contactCount'  => $contactCount,
                'categories'    => $categories,
            ]);
        });
    }
}
