<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\ConversationController as AdminConversationController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'entry'])->name('homePage');
Route::get('/login', [AuthController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'store'])->middleware(['guest', 'throttle:6,1'])->name('login.store');
Route::get('/register', [RegistrationController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegistrationController::class, 'store'])->middleware(['guest', 'throttle:6,1'])->name('register.store');
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/cart', [ShopController::class, 'cart'])->name('cart.index');
Route::post('/cart/{product}', [ShopController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/{product}', [ShopController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/{product}', [ShopController::class, 'removeFromCart'])->name('cart.remove');

Route::middleware(['auth', 'customer'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/messages', [ConversationController::class, 'index'])->name('conversations');
    Route::get('/messages/create', [ConversationController::class, 'create'])->name('conversations.create');
    Route::post('/messages', [ConversationController::class, 'store'])->name('conversations.store');
    Route::get('/messages/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/messages/{conversation}', [ConversationController::class, 'reply'])->name('conversations.reply');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminProductController::class, 'dashboard'])->name('dashboard');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}', [AdminReservationController::class, 'update'])->name('reservations.update');
    Route::get('/messages', [AdminConversationController::class, 'index'])->name('conversations.index');
    Route::get('/messages/{conversation}', [AdminConversationController::class, 'show'])->name('conversations.show');
    Route::post('/messages/{conversation}', [AdminConversationController::class, 'reply'])->name('conversations.reply');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'update'])->name('users.update');
});
