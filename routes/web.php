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
    // produk
    Route::get('daftar-produk', [HomeController::class, 'produk'])->name('daftar.produk');
    Route::get('hapus-produk/{id}', [HomeController::class, 'hapus_produk'])->name('hapus.produk');
    Route::match(['get', 'post'], '/tambah-produk', [HomeController::class, 'tambah_produk'])->name('tambah.produk');
    Route::match(['get','post'],'ubah-produk/{id}', [HomeController::class, 'ubah_produk'])->name('ubah.produk');

    // cart
    Route::match(['get', 'post'], 'cart/meja/{id_meja}', [HomeController::class, 'cart'])->name('cart');
    Route::get('/cart-quantity/{id}/{quantity}', [HomeController::class, 'cart_quantity'])->name('cart.quantity');
    Route::get('hapus-cart/{id}', [HomeController::class, 'hapus_cart']);
    Route::post('/add-to-cart', [HomeController::class, 'addToCart'])->name('cart.store');
    
    // laporan
    Route::get('laporan', [HomeController::class, 'laporan_new'])->name('laporan.index');

    // transaksi
    Route::get('transaksi', [HomeController::class, 'transaksi']);
    // invoice
    Route::get('invoice/{id}', [HomeController::class, 'invoice']);
    // order
    Route::match(['get', 'post'], 'order', [HomeController::class, 'order']);

    // meja
    Route::match(['get', 'post'], 'master-meja', [HomeController::class, 'master_meja'])->name('master.meja');
    Route::get('hapus-meja/{id}', [HomeController::class, 'hapus_meja'])->name('hapus.meja');
    Route::match(['get', 'post'],'edit-meja/{id}', [HomeController::class, 'edit_meja'])->name('edit.meja');

    // pesanan
    Route::get('pesanan', [HomeController::class, 'pesanan'])->name('pesanan');
    Route::get('pesanan-selesai/{id}', [HomeController::class, 'pesanan_selesai'])->name('selesai.order');
    Route::get('pesanan-detail/{id}', [HomeController::class, 'pesanan_detail'])->name('detail.order');
    Route::get('print-detail/{id}', [HomeController::class, 'print_pesanan_detail'])->name('print.order');
    
    
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
 
});

Route::get('/pesan/no_meja/{id_meja}', [HomeController::class, 'index_customer'])->name('pesan.meja');
Route::match(['get', 'post'], 'cart/meja/{id_meja}', [HomeController::class, 'cart'])->name('cart');
Route::get('/cart-quantity/{id}/{quantity}', [HomeController::class, 'cart_quantity'])->name('cart.quantity');
Route::get('hapus-cart/{id}', [HomeController::class, 'hapus_cart']);
Route::post('/add-to-cart-cust/{id_meja}', [HomeController::class, 'addToCartCust'])->name('cart.store');
Route::match(['get', 'post'], 'order-cust/{id_meja}', [HomeController::class, 'order']);


