<?php

use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Public Routes (Guest Mode)
Route::get('/catalog', [CatalogController::class, 'index'])->name('customer.catalog');
Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('customer.catalog.show');

// Cart Routes (Guest-accessible, localStorage-based)
Route::get('/cart', [CartController::class, 'index'])->name('customer.cart');
Route::post('/cart/check-stock', [CartController::class, 'checkStock'])->name('customer.cart.check-stock');

// Checkout Route (requires login)
Route::get('/orders/create', [OrderController::class, 'checkout'])->middleware('auth')->name('orders.checkout');

// Authenticated Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/status', [OrderController::class, 'status'])->name('orders.status');
    Route::get('/orders/create/{product}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/note', [AdminOrderController::class, 'updateNote'])->name('orders.update-note');

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::patch('/customers/{user}/toggle-active', [AdminCustomerController::class, 'toggleActive'])->name('customers.toggle-active');
});
