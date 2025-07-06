<?php

namespace App\Providers;

use App\Models\Contact;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use App\Models\Category;


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
        View::composer('*', function ($view) {
            if (View::exists('Layouts.admin')) {
                $view->with('contactCount', \App\Models\Contact::count());
            }
        });
        View::composer('*', function ($view) {
        $view->with('categories', Category::all());
    });

    }
}
