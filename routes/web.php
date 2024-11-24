<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\LoginRegisterController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\Api\BookApiController;

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

// Route untuk buku, tidak memerlukan middleware khusus
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');
Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

// Route untuk login dan register, tanpa middleware auth
Route::controller(LoginRegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

// Route untuk email tanpa middleware
Route::get('/send-mail', [SendEmailController::class, 'index'])->name('kirim-email');
Route::post('/post-email', [SendEmailController::class, 'store'])->name('post-email');


Route::resource('buku', BukuController::class);

// Route dengan middleware auth untuk users dan dashboard
Route::middleware(['auth'])->group(function () {
    // Route::redirect('dashboard', 'buku', 301)->name('dashboard');
    Route::get('/dashboard', [LoginRegisterController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [BukuController::class, 'index'])->name('users.index');
});

Route::resource('gallery', GalleryController::class);

Route::get('/buku/photo/{filename}', [BukuController::class, 'getPhoto'])->name('buku.photo');

Route::get('/info', [InfoController::class, 'index'])->name('info');