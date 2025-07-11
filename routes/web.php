<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\SlideController;
use App\Models\Banner;
use App\Models\Contact;
use App\Models\Slide;
use Illuminate\Routing\RouteGroup;
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
use Illuminate\Http\Request;
use App\Models\Wishlist;

use App\Models\CartItem;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;




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


Route::middleware(['admin.staff'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/brand/add', [BrandController::class, 'add_brand'])->name('admin.brand.add');
    Route::post('/admin/brand/store', [BrandController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brand/{id}/edit', [BrandController::class, 'edit_brand'])->name('admin.brand.edit');
    Route::put('/admin/brand/update', [BrandController::class, 'update_brand'])->name('admin.brand.update');
    Route::delete('/admin/brand/{id}/delete', [BrandController::class, 'delete_brand'])->name('admin.brand.delete');
    Route::get('/admin/brands', [BrandController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/search', [BrandController::class, 'brand_search'])->name('admin.brands.search');

    Route::get('/admin/categories', [CategoryController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/category/add', [CategoryController::class, 'add_category'])->name('admin.category.add');
    Route::post('/admin/category/store', [CategoryController::class, 'category_store'])->name('admin.category.store');
    Route::get('/admin/category/{id}/edit', [CategoryController::class, 'edit_category'])->name('admin.category.edit');
    Route::put('/admin/category/update', [CategoryController::class, 'update_category'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete', [CategoryController::class, 'delete_category'])->name('admin.category.delete');
    Route::get('/admin/categories/search', [CategoryController::class, 'category_search'])->name('admin.categories.search');

    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::put('/admin/order/status/update', [AdminController::class, 'updateStatus'])->name('admin.order.status.update');
    Route::get('/admin/order/create', [AdminController::class, 'order_create'])->name('admin.order.add');
    Route::post('/admin/order/store', [AdminController::class, 'order_store'])->name('admin.order.store');
    Route::get('/admin/products/find-by-code', [AdminController::class, 'findProductByCode'])->name('admin.products.findByCode');

    Route::get('/admin/order/detail/{id}', [AdminController::class, 'order_detail'])->name('admin.order.detail');

    Route::get('/admin/order/tracking', [AdminController::class, 'order_tracking'])->name('admin.order.tracking');
    // Route::put('/admin/order/update-status', [AdminController::class,'update_order_status'])->name('admin.order.status.update');

    Route::get('/admin/products', [ProductController::class, 'products'])->name('admin.products');
    Route::post('/admin/product/store', [ProductController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/product/add', [ProductController::class, 'add_product'])->name('admin.product.add');
    Route::get('/admin/product/{id}/edit', [ProductController::class, 'edit_product'])->name('admin.product.edit');
    Route::put('/admin/product/{id}', [ProductController::class, 'update_product'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete', [ProductController::class, 'delete_product'])->name('admin.product.delete');
    Route::get('/admin/product/{id}/detail', [ProductController::class, 'product_detail'])->name('admin.product.detail');
    Route::get('/admin/products/search', [ProductController::class, 'product_search'])->name('admin.products.search');



    Route::get('/admin/sliders', [SlideController::class, 'sliders'])->name('admin.sliders');
    Route::get('/admin/slide/add', [SlideController::class, 'add_slide'])->name('admin.slide.add');
    Route::post('/admin/slide/store', [SlideController::class, 'slide_store'])->name('admin.slide.store');
    Route::get('/admin/slide/{id}/edit', [SlideController::class, 'edit_slide'])->name('admin.slide.edit');
    Route::put('/admin/slide/{id}', [SlideController::class, 'update_slide'])->name('admin.slide.update');
    Route::delete('/admin/slide/{id}/delete', [SlideController::class, 'delete_slide'])->name('admin.slide.delete');
    Route::put('/admin/slide/{id}/toggle', [SlideController::class, 'toggle_slide_status'])->name('admin.slide.toggle');

    Route::get('/admin/banners', [BannerController::class, 'banners'])->name('admin.banners');
    Route::get('/admin/banner/add', [BannerController::class, 'add_banner'])->name('admin.banner.add');
    Route::post('/admin/banner/store', [BannerController::class, 'banner_store'])->name('admin.banner.store');
    Route::get('/admin/banner/{id}/edit', [BannerController::class, 'edit_banner'])->name('admin.banner.edit');
    Route::put('/admin/banner/{id}', [BannerController::class, 'update_banner'])->name('admin.banner.update');
    Route::delete('/admin/banner/{id}/delete', [BannerController::class, 'banner_slide'])->name('admin.banner.delete');
    Route::put('/admin/banner/{id}/toggle', [BannerController::class, 'toggle_banner_status'])->name('admin.banner.toggle');


    Route::get('/admin/users', [UserController::class, 'users'])->name('admin.users');
    Route::get('/admin/user/add', [UserController::class, 'add_user'])->name('admin.user.add');
    Route::post('/admin/user/store', [UserController::class, 'user_store'])->name('admin.user.store');
    Route::get('/admin/user/{id}/edit', [UserController::class, 'edit_user'])->name('admin.user.edit');
    Route::put('/admin/user/update', [UserController::class, 'update_user'])->name('admin.user.update');
    Route::delete('/admin/user/{id}/delete', [UserController::class, 'delete_user'])->name('admin.user.delete');
    Route::get('/admin/user/search', [UserController::class, 'search_user'])->name('admin.users.search');

    Route::get('/admin/coupons', [CouponController::class, 'coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add', [CouponController::class, 'add_coupon'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store', [CouponController::class, 'coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/{id}/edit', [CouponController::class, 'edit_coupon'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update/{id}', [CouponController::class, 'update_coupon',])->name('admin.coupon.update');
    Route::delete('/admin/coupon/{id}/delete', [CouponController::class, 'delete_coupon'])->name('admin.coupon.delete');

    Route::get('/comments', [AdminController::class, 'comments'])->name('admin.comments');
    Route::put('/comments/{id}/toggle', [AdminController::class, 'comment_toggle'])->name('admin.comment.toggle');
    Route::delete('/comments/{id}', [AdminController::class, 'delete_comment'])->name('admin.review.delete');

    Route::put('/admin/setting/{id}', [AdminController::class, 'setting'])->name('admin.setting');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');

    Route::get('/admin/password/change', [AdminController::class, 'changePassword'])->name('admin.password.change');
    Route::post('/admin/password/change', [AdminController::class, 'updatePassword'])->name('admin.password.update');
});

//Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

Route::put('/admin/order/{id}/deliver', [AdminController::class, 'markAsDelivered'])->name('admin.order.status.deliver');


Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('notifications');


// VD: trang chào mừng sau khi đăng ký
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [HomeController::class, 'showLoginForm'])->name('Login');
Route::post('/login', [HomeController::class, 'login'])->name('login');

Route::post('/logout', [HomeController::class, 'logout'])
    ->name('logout');




Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/shop/category/{slug}', [HomeController::class, 'categoryProducts'])->name('shop.category');
Route::get('/shop/brand/{slug}', [HomeController::class, 'brandProducts'])->name('shop.brand');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/products', [AdminController::class, 'products'])->name('products');

Route::get('/password/change', [AdminController::class, 'changePassword'])->name('auth.password.change');
Route::post('/password/change', [AdminController::class, 'updatePassword'])->name('auth.password.update');



//Route::get('/password/reset', [HomeController::class, 'showResetForm'])->name('password.request');
//Route::post('/password/email', [HomeController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::post('/password/reset', [HomeController::class, 'reset'])->name('password.update');
// Route::get('/password/reset/{token}', [HomeController::class, 'showResetFormWithToken'])->name('password.reset.token');
Route::get('/password/confirm', [HomeController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('/password/confirm', [HomeController::class, 'confirm'])->name('password.confirm.submit');


Route::get('/contact', [UserController::class, 'contact'])->name('contact');
Route::get('/admin/contacts', [ContactController::class, 'contacts'])->name('admin.contacts');
Route::post('/contact/store', [ContactController::class, 'contactStore'])->name('contact.store');
Route::delete('/contact/delete/{id}', [ContactController::class, 'delete_contact'])->name('contact.delete');


Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::get('/wishlist', [HomeController::class, 'wishlist'])->name('wishlist');

Route::post('/cart/add-detail', [CartController::class, 'addDetail'])->name('cart.addDetail');
Route::delete('/cart/remove/{slug}', [CartController::class, 'remove'])->name('cart.remove');


Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');



Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
Route::put('/orders/{order}/refund', [OrderController::class, 'refund'])->name('orders.refund');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');


Route::middleware('auth')->group(function () {
    Route::get('/account-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/account-orders/{id}', [OrderController::class, 'show'])->name('orders.details');
});
Route::post('/register', [HomeController::class, 'register'])->name('register.submit');
Route::middleware(['auth'])->group(function () {
    Route::get('/account', [UserController::class, 'index'])->name('account');
    Route::post('/account/update', [UserController::class, 'update'])->name('account.update');
    Route::post('/account/change-password', [UserController::class, 'changePassword'])->name('account.changePassword');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::put('/profile/avatar', [UserController::class, 'updateAvatar'])->name('profile.avatar');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
    Route::get('/profile', [UserController::class, 'Profile'])->name('profile');

    Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.change.password');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('cart.applyCoupon');
    Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('cart.removeCoupon');
    Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('user.checkoutsuccess');
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
    Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('review.edit');

    Route::put('/review/{id}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');

    Route::get('/notifications', [NotificationController::class, 'indexUser'])->name('user.notifications');
});

// web.php

Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('product.reviews');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::get('/api/cart-wishlist-counts', function () {
    if (Auth::check()) {
        return response()->json([
            'cartItemCount' => CartItem::where('user_id', Auth::id())->sum('quantity'),
            'wishlistCount' => Wishlist::where('user_id', Auth::id())->count()
        ]);
    } else {
        $cart = session('cart', []);
        $wishlist = session('wishlist', []);
        return response()->json([
            'cartItemCount' => collect($cart)->sum('quantity'),
            'wishlistCount' => collect($wishlist)->sum('quantity')
        ]);
    }
});





Route::post('/register', [HomeController::class, 'register'])->name('register.submit');



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


Route::get('/hot-deals', [HomeController::class, 'showHotDeals'])->name('hot.deals');
