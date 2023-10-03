<?php

use App\Http\Controllers\CartIndexController;
use App\Http\Controllers\CategoryIndexController;
use App\Http\Controllers\CategoryShowController;
use App\Http\Controllers\CheckoutIndexController;
use App\Http\Controllers\OrderConfirmController;
use App\Http\Controllers\OrderIndexController;
use App\Http\Controllers\ProductIndexController;
use App\Http\Controllers\ProductShowController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RedirectIfEmptyCart;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/categories', CategoryIndexController::class);
Route::get('/', CategoryShowController::class);
Route::get('/products', ProductIndexController::class)->name('products.index');
Route::get('/products/{product:slug}', ProductShowController::class)->name('products.show');

Route::get('/cart', CartIndexController::class)->name('cart.index');
Route::get('checkout', CheckoutIndexController::class)->name('checkout.index')->middleware(RedirectIfEmptyCart::class);
Route::view('product', 'products.single-product');
Route::get('/orders/{order:uuid}/confirmation', OrderConfirmController::class)->name('order.confirm');
Route::get('/orders', OrderIndexController::class)->middleware('auth')->name('orders.index');

