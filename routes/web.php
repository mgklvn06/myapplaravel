<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// public shop
use App\Http\Controllers\ProductController;
Route::get('/', [ProductController::class,'index'])->name('home');
Route::get('/product/{product:slug}', [ProductController::class,'show'])->name('product.show');

// cart & checkout
Route::get('/cart', [CartController::class,'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class,'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class,'remove'])->name('cart.remove');

Route::middleware('auth')->group(function(){
    Route::get('/checkout', [CheckoutController::class,'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class,'store'])->name('checkout.store');
    Route::get('/orders/{order}', [OrderController::class,'show'])->name('orders.show'); // user orders
});


// ADMIN only area
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', function(){ return redirect()->route('admin.products.index'); })->name('dashboard');
    Route::resource('products', AdminProductController::class);
});

// CUSTOMER-only example (for account pages, orders, checkout)
Route::prefix('account')->name('account.')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('dashboard', function(){ return view('account.dashboard'); })->name('dashboard');
    Route::get('orders', function(){ /* ... */ })->name('orders.index');
});

// OR allow either admin OR customer on a route:
Route::get('/special', function(){
    return 'allowed for admin or customer';
})->middleware(['auth','role:admin,customer']);

// admin (simple gate or middleware)
Route::middleware(['auth','can:manage-products'])->prefix('admin')->group(function(){
    Route::resource('products', AdminProductController::class);
    Route::resource('orders', AdminOrderController::class);
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
