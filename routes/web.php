<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\BookedController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [BookingController::class, 'index'])
     ->name('landing-page');
Route::post('/book-service', [BookingController::class, 'bookService'])
     ->name('book.service');

     Route::get('/payment/finish', [BookingController::class, 'finish'])->name('payment.finish');


Route::post('/login', [CustomAuthenticatedSessionController::class, 'store']);

Route::post('/logout',[CustomAuthenticatedSessionController::class, 'destroy'])
     ->name('logout');



Route::middleware(['auth','role:admin'])
    ->group(function(){
        // Dashboard
        Route::get('/dashboard', fn() => view('admin.dashboard'))
             ->name('dashboard');

        // CRUD resource controllers
     Route::resource('admin', AdminController::class)
     ->parameters(['' => 'admin']);
        Route::resource('pelanggan', PelangganController::class);
        Route::resource('perawatan', PerawatanController::class);
        Route::resource('pemesanan', PemesananController::class);
        Route::resource('pembayaran',PembayaranController::class);
        Route::resource('booked',    BookedController::class);

        // Laporan & Pengaturan
        Route::get('/report', fn() => view('admin.report'))
             ->name('report');
    });


// Area Pelanggan (dummy)
Route::middleware(['auth','role:pelanggan'])
    ->group(function(){
        Route::get('/kk', fn() => 2)->name('home');
        Route::get('reservasi', fn() => 2)->name('reservasi.index');
        Route::get('reservasi/create', fn() => 2)->name('reservasi.create');
        Route::post('reservasi', fn() => 2)->name('reservasi.store');
    });
