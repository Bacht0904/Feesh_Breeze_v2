<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::get('/admin/password/reset', [AdminController::class, 'showResetForm'])->name('admin.password.reset');
Route::post('/admin/password/email', [AdminController::class, 'sendResetLinkEmail'])->name('admin.password.email');
Route::post('/admin/password/reset', [AdminController::class, 'reset'])->name('admin.password.update');
Route::get('/admin/password/reset/{token}', [AdminController::class, 'showResetFormWithToken'])->name('admin.password.reset.token');
Route::get('/admin/password/confirm', [AdminController::class, 'showConfirmForm'])->name('admin.password.confirm');
Route::post('/admin/password/confirm', [AdminController::class, 'confirm'])->name('admin.password.confirm.submit');
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
});


Auth::routes();

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

Route::get('/admin/brand/add', [AdminController::class, 'add_brand'])->name('admin.brand.add');
Route::post('/admin/brand/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
Route::get('/admin/brand/{id}/edit', [AdminController::class, 'edit_brand'])->name('admin.brand.edit');
Route::put('/admin/brand/update', [AdminController::class, 'update_brand'])->name('admin.brand.update');
Route::delete('/admin/brand/{id}/delete', [AdminController::class, 'delete_brand'])->name('admin.brand.delete');
Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
Route::get('/admin/brands/search', [AdminController::class, 'brand_search'])->name('admin.brands.search');

Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
Route::get('/admin/category/add', [AdminController::class, 'add_category'])->name('admin.category.add');
Route::post('/admin/category/store', [AdminController::class, 'category_store'])->name('admin.category.store');
Route::get('/admin/category/{id}/edit', [AdminController::class, 'edit_category'])->name('admin.category.edit');
Route::put('/admin/category/update', [AdminController::class, 'update_category'])->name('admin.category.update');
Route::delete('/admin/category/{id}/delete', [AdminController::class, 'delete_category'])->name('admin.category.delete');

Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
Route::get('/admin/order/detail', [AdminController::class, 'order_detail'])->name('admin.order.detail');
Route::get('/admin/order/tracking', [AdminController::class, 'order_tracking'])->name('admin.order.tracking');

Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
Route::post('/admin/product/store', [AdminController::class, 'product_store'])->name('admin.product.store');
Route::get('/admin/product/add', [AdminController::class, 'add_product'])->name('admin.product.add');
Route::get('/admin/product/{id}/edit', [AdminController::class, 'edit_product'])->name('admin.product.edit');
Route::put('/admin/product/{id}', [AdminController::class, 'update_product'])->name('admin.product.update');
Route::delete('/admin/product/{id}/delete', [AdminController::class, 'delete_product'])->name('admin.product.delete');
Route::get('/admin/product/{id}/detail', [AdminController::class, 'product_detail'])->name('admin.product.detail');
Route::get('/admin/products/search', [AdminController::class, 'product_search'])->name('admin.products.search');


Route::get('/admin/sliders', [AdminController::class, 'sliders'])->name('admin.sliders');
Route::get('/admin/slide/add', [AdminController::class, 'add_slide'])->name('admin.slide.add');
Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');

Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
Route::get('/admin/coupon/add', [AdminController::class, 'add_coupon'])->name('admin.coupon.add');
Route::post('/admin/coupon/store', [AdminController::class, 'coupon_store'])->name('admin.coupon.store');
Route::get('/admin/coupon/{id}/edit', [AdminController::class, 'edit_coupon'])->name('admin.coupon.edit');
Route::put('/admin/coupon/update', [AdminController::class, 'update_coupon'])->name('admin.coupon.update');
Route::delete('/admin/coupon/{id}/delete', [AdminController::class, 'delete_coupon'])->name('admin.coupon.delete');

Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/login', [HomeController::class, 'showLoginForm'])->name('Login');
Route::post('/login', [HomeController::class, 'login'])->name('login');

Route::get('/logout','HomeController@logout')->name('user.logout');

Route::get('/profile', [UserController::class, 'Profile'])->name('profile');
Route::get('/register', [HomeController::class, 'showRegistrationForm'])->name('register');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/shop/category/{slug}', [HomeController::class, 'categoryProducts'])->name('shop.category');
Route::get('/shop/brand/{slug}', [HomeController::class, 'brandProducts'])->name('shop.brand');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/products', [AdminController::class, 'products'])->name('products');

Route::get('/products/{slug}', [AdminController::class, 'show'])->name('products.show');
Route::post('/register', [HomeController::class, 'register'])->name('register.submit');
Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.change.password');
Route::get('/password/reset', [HomeController::class, 'showResetForm'])->name('password.request');
Route::post('/password/email', [HomeController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/password/reset', [HomeController::class, 'reset'])->name('password.update');
Route::get('/password/reset/{token}', [HomeController::class, 'showResetFormWithToken'])->name('password.reset.token');
Route::get('/password/confirm', [HomeController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('/password/confirm', [HomeController::class, 'confirm'])->name('password.confirm.submit');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart');
Route::get('/wishlist', [HomeController::class, 'wishlist'])->name('wishlist');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
