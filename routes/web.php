<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\VNPayController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;




Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
//Route::get('/admin/password/reset', [AdminController::class, 'showResetForm'])->name('admin.password.reset');
//Route::post('/admin/password/email', [AdminController::class, 'sendResetLinkEmail'])->name('admin.password.email');
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
Route::get('/admin/categories/search', [AdminController::class, 'category_search'])->name('admin.categories.search');

Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
Route::get('/admin/order/{id}detail', [AdminController::class, 'order_detail'])->name('admin.order.detail');
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
Route::post('/admin/slide/store', [AdminController::class, 'slide_store'])->name('admin.slide.store');
Route::get('/admin/slide/{id}/edit', [AdminController::class, 'edit_slide'])->name('admin.slide.edit');
Route::put('/admin/slide/{id}', [AdminController::class, 'update_slide'])->name('admin.slide.update');
Route::delete('/admin/slide/{id}/delete', [AdminController::class, 'delete_slide'])->name('admin.slide.delete');
Route::put('/admin/slide/{id}/toggle', [AdminController::class, 'toggle_slide_status'])->name('admin.slide.toggle');


Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
Route::get('/admin/user/add', [AdminController::class, 'add_user'])->name('admin.user.add');
Route::post('/admin/user/store', [AdminController::class, 'user_store'])->name('admin.user.store');
Route::get('/admin/user/{id}/edit', [AdminController::class, 'edit_user'])->name('admin.user.edit');
Route::put('/admin/user/update', [AdminController::class, 'update_user'])->name('admin.user.update');
Route::delete('/admin/user/{id}/delete', [AdminController::class, 'delete_user'])->name('admin.user.delete');

Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
Route::get('/admin/coupon/add', [AdminController::class, 'add_coupon'])->name('admin.coupon.add');
Route::post('/admin/coupon/store', [AdminController::class, 'coupon_store'])->name('admin.coupon.store');
Route::get('/admin/coupon/{id}/edit', [AdminController::class, 'edit_coupon'])->name('admin.coupon.edit');
Route::put('/admin/coupon/update/{id}', [AdminController::class, 'update_coupon',])->name('admin.coupon.update');
Route::delete('/admin/coupon/{id}/delete', [AdminController::class, 'delete_coupon'])->name('admin.coupon.delete');

Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/login', [HomeController::class, 'showLoginForm'])->name('Login');
Route::post('/login', [HomeController::class, 'login'])->name('login');

Route::post('/logout', [HomeController::class, 'logout'])
    ->name('logout');


Route::get('/profile', [UserController::class, 'Profile'])->name('profile');
Route::get('/register', [HomeController::class, 'showRegistrationForm'])->name('register');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/shop/category/{slug}', [HomeController::class, 'categoryProducts'])->name('shop.category');
Route::get('/shop/brand/{slug}', [HomeController::class, 'brandProducts'])->name('shop.brand');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/products', [AdminController::class, 'products'])->name('products');

Route::get('/password/change', [AdminController::class, 'changePassword'])->name('auth.password.change');
Route::post('/passwords/change/store', [AdminController::class, 'changePasswordStore'])->name('auth.password.change.store');

Route::get('/products/{slug}', [AdminController::class, 'show'])->name('products.show');
Route::post('/register', [HomeController::class, 'register'])->name('register.submit');
Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.change.password');
//Route::get('/password/reset', [HomeController::class, 'showResetForm'])->name('password.request');
//Route::post('/password/email', [HomeController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::post('/password/reset', [HomeController::class, 'reset'])->name('password.update');
// Route::get('/password/reset/{token}', [HomeController::class, 'showResetFormWithToken'])->name('password.reset.token');
Route::get('/password/confirm', [HomeController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('/password/confirm', [HomeController::class, 'confirm'])->name('password.confirm.submit');

Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::get('/wishlist', [HomeController::class, 'wishlist'])->name('wishlist');

Route::post('/cart/add-detail', [CartController::class, 'addDetail'])->name('cart.addDetail');
Route::get('/cart/remove/{slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart/thank-you', [CartController::class, 'thankYou'])->name('cart.thankYou');
Route::post('/add-to-cart', [CartController::class, 'addDetail'])->name('cart.addDetail');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

Route::get('/forgot-password', [ForgotPasswordController::class,'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class,'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class,'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class,'resetPassword'])->name('password.update');


Route::middleware('auth')->group(function () {
    Route::get('/account-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/account-orders/{id}', [OrderController::class, 'show'])->name('orders.details');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/account', [UserController::class, 'index'])->name('account');
    Route::post('/account/update', [UserController::class, 'update'])->name('account.update');
    Route::post('/account/change-password', [UserController::class, 'changePassword'])->name('account.changePassword');
});

// web.php
Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('review.edit');

Route::put('/review/{id}', [ReviewController::class, 'update'])->name('review.update');
Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');
Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('product.reviews');




Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('cart.removeCoupon');

Route::get('/vnpay-payment', [VNPayController::class, 'createPayment'])->name('vnpay.payment');
Route::get('/vnpay-return', [VNPayController::class, 'return'])->name('vnpay.return');


Route::match(['GET', 'POST'], '/momo-return', [CheckoutController::class, 'handleMomoCallback'])->name('momo.callback');


// routes/web.php
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::get('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
Route::post('/wishlist/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');

// routes/web.php hoặc routes/api.php
Route::get('/search-suggestions', [HomeController::class, 'suggest'])->name('search.suggest');
Route::get('/quick-suggestions', [HomeController::class, 'quickSuggestions']);

