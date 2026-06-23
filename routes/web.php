<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// Public Routes
// ──────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');

// ──────────────────────────────────────────────
// Cart Routes (no auth required)
// ──────────────────────────────────────────────

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

// ──────────────────────────────────────────────
// Auth-Required Routes
// ──────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

    // Customer Dashboard
    Route::prefix('my-account')->group(function () {
        Route::get('/', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
        Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('customer.orders');
        Route::get('/orders/{order}', [CustomerDashboardController::class, 'orderShow'])->name('customer.orders.show');
        Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('customer.profile');
        Route::put('/profile', [CustomerDashboardController::class, 'profileUpdate'])->name('customer.profile.update');
    });
});

// ──────────────────────────────────────────────
// Admin Routes (auth + admin middleware)
// ──────────────────────────────────────────────

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
    Route::resource('products', AdminProductController::class)->names('admin.products');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/customers/{user}', [AdminCustomerController::class, 'show'])->name('admin.customers.show');
});

// ──────────────────────────────────────────────
// Auth scaffolding routes (Breeze / Fortify)
// ──────────────────────────────────────────────

require __DIR__.'/auth.php';
