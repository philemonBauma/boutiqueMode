<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('homePage');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/cart', [ShopController::class, 'cart'])->name('cart.index');
Route::post('/cart/{product}', [ShopController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/{product}', [ShopController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/{product}', [ShopController::class, 'removeFromCart'])->name('cart.remove');
