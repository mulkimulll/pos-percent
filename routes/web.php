<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

Route::get('/', [AuthController::class, 'showFormLogin'])->name('login');
Route::get('login', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showFormRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
 
Route::group(['middleware' => 'auth'], function () {
 
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('daftar-produk', [HomeController::class, 'produk'])->name('daftar.produk');
    Route::match(['get', 'post'], 'cart', [HomeController::class, 'cart'])->name('cart');
    Route::get('/cart-quantity/{id}/{quantity}', [HomeController::class, 'cart_quantity'])->name('cart.quantity');
    Route::get('hapus-cart/{id}', [HomeController::class, 'hapus_cart']);
    Route::post('/add-to-cart', [HomeController::class, 'addToCart'])->name('cart.store');
    Route::match(['get', 'post'], '/tambah-produk', [HomeController::class, 'tambah_produk'])->name('tambah.produk');
    Route::get('hapus-produk', [HomeController::class, 'hapus_produk']);
    Route::get('laporan', [HomeController::class, 'laporan_new'])->name('laporan.index');
    Route::get('transaksi', [HomeController::class, 'transaksi']);
    Route::get('invoice/{id}', [HomeController::class, 'invoice']);
    Route::match(['get', 'post'], 'order', [HomeController::class, 'order']);


    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
 
});

Route::get('menu', [HomeController::class, 'index'])->name('home');
Route::match(['get', 'post'], 'cart', [HomeController::class, 'cart'])->name('cart');
Route::get('/cart-quantity/{id}/{quantity}', [HomeController::class, 'cart_quantity'])->name('cart.quantity');
Route::get('hapus-cart/{id}', [HomeController::class, 'hapus_cart']);
Route::post('/add-to-cart-cust', [HomeController::class, 'addToCart'])->name('cart.store');
