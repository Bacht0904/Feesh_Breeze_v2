<?php

namespace App\Providers;

use App\Models\Contact;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use App\Models\Category;
use App\Models\Comment;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            // Giỏ hàng
            $cart = session('cart', []);
            $cartItemCount = collect($cart)->sum('quantity');

            // Số lượng liên hệ
            $contactCount = Contact::count();

            // Số lượng bình luận chưa duyệt
            $pendingComments = Comment::where('is_approved', false)->count();

            // Danh sách loại sản phẩm
            $categories = Category::all();

            // Gắn vào view
            $view->with([
                'cartItemCount' => $cartItemCount,
                'contactCount' => $contactCount,
                'pendingComments' => $pendingComments,
                'categories' => $categories,
            ]);
        });
    }
}
