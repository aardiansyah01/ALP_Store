<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

Route::get('/', function () {
    return view('welcome');
});

/* AUTH */
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* CUSTOMER */
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
});

Route::patch('/cart/{id}/toggle', [CartController::class, 'toggle'])
    ->name('cart.toggle');

Route::patch('/cart/{id}/quantity', [CartController::class, 'updateQuantity'])
    ->name('cart.updateQuantity');

Route::put('/cart/{id}/size', [CartController::class, 'updateSize'])
    ->name('cart.updateSize');


Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout/store', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])
        ->name('orders.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])
        ->name('orders.complete');

    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('orders.cancel');

    Route::post('/orders/{order}/buy-again', [OrderController::class, 'buyAgain'])
        ->name('orders.buyAgain');
});

/* ADMIN */
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
});

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index'])
        ->name('admin.orders.index');

    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('admin.orders.updateStatus');
});