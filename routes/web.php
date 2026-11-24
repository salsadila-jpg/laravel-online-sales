<?php

use Illuminate\Support\Facades\Route;

// Authentication Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LogoutController;

// Customer Controllers
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ShippingServiceController;
use App\Http\Controllers\Admin\StockController;

/*
|--------------------------------------------------------------------------
| Homepage
|--------------------------------------------------------------------------
*/

Route::get('/', [ShopController::class, 'index'])
    ->name('home');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');
});

// Logout
Route::post('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Product Catalog
|--------------------------------------------------------------------------
*/

Route::get('/products', [ProductCatalogController::class, 'index'])
    ->name('products.index');

Route::get('/products/{product}', [ProductCatalogController::class, 'show'])
    ->name('products.show');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer'])->group(function () {

    // Customer Dashboard
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    // Shopping Cart
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/{product}', [CartController::class, 'store'])
        ->name('cart.store');

    Route::patch('/cart/{product}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/{product}', [CartController::class, 'destroy'])
        ->name('cart.destroy');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    // Customer Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])
        ->name('orders.show');

    // Customer Profile
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Product Management
        Route::resource('products', ProductController::class);

        // Category Management
        Route::resource('categories', CategoryController::class);

        // Order Management
        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        // Confirm Pending Order
        Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm'])
            ->name('orders.confirm');

        // Update Order Status
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        // Cancel Order
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
            ->name('orders.cancel');

        // Shipping Services
        Route::resource('shipping-services', ShippingServiceController::class);

        // Stock Management
        Route::get('/stocks', [StockController::class, 'index'])
            ->name('stocks.index');

        Route::put('/stocks/{product}', [StockController::class, 'update'])
            ->name('stocks.update');
    });
