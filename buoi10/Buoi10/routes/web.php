<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| Web Routes - E-Commerce (Group A & Group B Full Features)
|--------------------------------------------------------------------------
*/

// 1. Storefront Routes (fashion_store-web template)
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/collection', [StoreController::class, 'collection'])->name('collection');
Route::get('/men', [StoreController::class, 'men'])->name('men');
Route::get('/women', [StoreController::class, 'women'])->name('women');
Route::get('/flash-sale', [StoreController::class, 'flashSale'])->name('flash.sale');
Route::get('/product/{slug}', [StoreController::class, 'single'])->name('product.single');
Route::get('/articles', [StoreController::class, 'articles'])->name('articles');
Route::get('/contact', [StoreController::class, 'contact'])->name('contact');
Route::post('/contact', [StoreController::class, 'submitContact'])->name('contact.submit');
Route::get('/privacy', [StoreController::class, 'privacy'])->name('privacy');
Route::post('/chatbot/message', [ChatbotController::class, 'message'])->name('chatbot.message');

// 2. Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/signup', [AuthController::class, 'showRegister'])->name('register');
Route::post('/signup', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']);

// 3. Cart, Voucher & Checkout Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/quick-add/{product_id}', function ($product_id) {
    return app(CartController::class)->add(new \Illuminate\Http\Request(['product_id' => $product_id, 'quantity' => 1]));
})->name('cart.quick.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::get('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.place');
Route::get('/payment/online/{order_code}', [CartController::class, 'onlinePayment'])->name('checkout.payment');
Route::post('/payment/online/{order_code}/confirm', [CartController::class, 'confirmOnlinePayment'])->name('checkout.payment.confirm');

// 4. Order Tracking, Wishlist, Reviews & Customer Account
Route::get('/track-order', [AccountController::class, 'trackOrder'])->name('order.track');
Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist.index');
Route::get('/wishlist/toggle/{product_id}', [AccountController::class, 'toggleWishlist'])->name('wishlist.toggle');
Route::post('/product/{product_id}/review', [AccountController::class, 'storeReview'])->name('product.review');
Route::get('/order-detail/{order_code}', [AccountController::class, 'orderDetail'])->name('account.order.detail');
Route::get('/notification/read/{id}', [AccountController::class, 'markNotificationRead'])->name('notification.read');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/account/update', [AccountController::class, 'updateProfile'])->name('account.update');
    Route::post('/account/password', [AccountController::class, 'changePassword'])->name('account.password');
    Route::post('/account/address', [AccountController::class, 'storeAddress'])->name('account.address.store');
    Route::get('/account/address/{id}/default', [AccountController::class, 'setDefaultAddress'])->name('account.address.default');
    Route::get('/account/address/{id}/delete', [AccountController::class, 'deleteAddress'])->name('account.address.delete');
    Route::post('/order/{order_id}/return', [AccountController::class, 'submitReturn'])->name('account.order.return');
});

// 5. Shipper Mobile Companion App (Group B: Tích hợp app giao hàng điện thoại)
Route::get('/shipper', [AdminController::class, 'shipperApp'])->name('shipper.app');
Route::post('/shipper/order/{id}/update', [AdminController::class, 'shipperUpdate'])->name('shipper.order.update');

// 6. Admin Portal Routes (admin4 template - Protected by role:admin,staff)
Route::prefix('admin')->middleware('role:admin,staff')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    // Products & Variants & Inventory
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/add', [AdminController::class, 'addProduct'])->name('admin.products.add');
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::post('/products/{id}/update', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{id}/delete', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    Route::get('/inventory', [AdminController::class, 'inventory'])->name('admin.inventory');
    Route::post('/inventory/{product_id}/variant', [AdminController::class, 'storeVariant'])->name('admin.variant.store');
    Route::get('/inventory/variant/{id}/delete', [AdminController::class, 'deleteVariant'])->name('admin.variant.delete');
    Route::post('/inventory/{id}/stock', [AdminController::class, 'updateStock'])->name('admin.stock.update');

    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/categories/add', [AdminController::class, 'addCategory'])->name('admin.categories.add');
    Route::post('/categories/store', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::delete('/categories/{id}/delete', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

    // Brands
    Route::get('/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/brands/add', [AdminController::class, 'addBrand'])->name('admin.brands.add');
    Route::post('/brands/store', [AdminController::class, 'storeBrand'])->name('admin.brands.store');
    Route::delete('/brands/{id}/delete', [AdminController::class, 'deleteBrand'])->name('admin.brands.delete');

    // Orders & Tracking
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/orders/{id}', [AdminController::class, 'orderDetails'])->name('admin.orders.detail');
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    Route::get('/order-tracking', [AdminController::class, 'orderTracking'])->name('admin.order.tracking');

    // Return / Exchange Requests
    Route::get('/returns', [AdminController::class, 'returns'])->name('admin.returns');
    Route::post('/returns/{id}/update', [AdminController::class, 'updateReturn'])->name('admin.returns.update');

    // Reviews
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
    Route::delete('/reviews/{id}/delete', [AdminController::class, 'deleteReview'])->name('admin.reviews.delete');

    // Coupons / Vouchers
    Route::get('/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/coupons/add', [AdminController::class, 'addCoupon'])->name('admin.coupons.add');
    Route::post('/coupons/store', [AdminController::class, 'storeCoupon'])->name('admin.coupons.store');
    Route::delete('/coupons/{id}/delete', [AdminController::class, 'deleteCoupon'])->name('admin.coupons.delete');

    // Sliders
    Route::get('/sliders', [AdminController::class, 'sliders'])->name('admin.sliders');
    Route::get('/sliders/add', [AdminController::class, 'addSlide'])->name('admin.sliders.add');
    Route::post('/sliders/store', [AdminController::class, 'storeSlide'])->name('admin.sliders.store');
    Route::delete('/sliders/{id}/delete', [AdminController::class, 'deleteSlide'])->name('admin.sliders.delete');

    // Users & Roles (RBAC)
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');

    // Sales Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
});
