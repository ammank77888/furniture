<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RecommendationController;

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

// Landing page guest & user
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/katalog', [ProductController::class, 'index'])->name('katalog');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.detail');



// Proteksi keranjang, checkout, riwayat transaksi
Route::middleware(['auth'])->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/riwayat', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/riwayat/{id}/konfirmasi', [TransactionController::class, 'confirm'])->name('transactions.confirm');
    
    // Rekomendasi
    Route::get('/rekomendasi', [RecommendationController::class, 'index'])->name('recommendations.index');
    Route::post('/track-activity', [RecommendationController::class, 'trackActivity'])->name('recommendations.track');
    Route::get('/rekomendasi/api', [RecommendationController::class, 'getRecommendations'])->name('recommendations.api');
});

// Authentication Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Email Verification Routes
Route::get('email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');

// Admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('produk/create', [AdminController::class, 'create'])->name('admin.produk.create');
    Route::post('produk', [AdminController::class, 'store'])->name('admin.produk.store');
    Route::get('produk/{id}/edit', [AdminController::class, 'edit'])->name('admin.produk.edit');
    Route::put('produk/{id}', [AdminController::class, 'update'])->name('admin.produk.update');
    Route::delete('produk/{id}', [AdminController::class, 'destroy'])->name('admin.produk.destroy');
    Route::get('transaksi/{id}/edit', [AdminController::class, 'editTransaction'])->name('admin.transaksi.edit');
    Route::put('transaksi/{id}', [AdminController::class, 'updateTransaction'])->name('admin.transaksi.update');
    Route::delete('transaksi/{id}', [AdminController::class, 'destroyTransaction'])->name('admin.transaksi.destroy');
    Route::get('transaksi', [AdminController::class, 'listTransaction'])->name('admin.transaksi.index');
    Route::get('produk', [AdminController::class, 'produkIndex'])->name('admin.produk.index');
});

