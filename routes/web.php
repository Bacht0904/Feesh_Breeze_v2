<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;


// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/admin', [AdminController::class, 'admin'])->name('admin');
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/login', [HomeController::class, 'showLoginForm'])->name('Login');
Route::get('/register', [HomeController::class, 'showRegistrationForm'])->name('register');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart');
Route::get('/wishlist', [HomeController::class, 'wishlist'])->name('wishlist');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
