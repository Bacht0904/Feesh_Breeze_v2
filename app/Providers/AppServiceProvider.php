<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;


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
            $cart = session('cart', []);
            $cartItemCount = collect($cart)->sum('quantity');
            $view->with('cartItemCount', $cartItemCount);
        });
    }
}
