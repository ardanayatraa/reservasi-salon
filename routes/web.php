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
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ShiftController;

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
Route::post('/check-availability', [BookingController::class, 'checkAvailability'])
    ->name('check.availability');

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
        // Karyawan
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('/karyawan/{karyawan}', [KaryawanController::class, 'show'])->name('karyawan.show');
        Route::get('/karyawan/{karyawan}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('/karyawan/{karyawan}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/karyawan/{karyawan}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

        // Shift
        Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
        Route::get('/shift/create', [ShiftController::class, 'create'])->name('shift.create');
        Route::post('/shift', [ShiftController::class, 'store'])->name('shift.store');
        Route::get('/shift/{shift}/edit', [ShiftController::class, 'edit'])->name('shift.edit');
        Route::put('/shift/{shift}', [ShiftController::class, 'update'])->name('shift.update');
        Route::delete('/shift/{shift}', [ShiftController::class, 'destroy'])->name('shift.destroy');

        Route::get('laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');

        Route::get('laporan/pdf', [LaporanController::class, 'exportPdf'])
            ->name('laporan.pdf');
    });


// Area Pelanggan (dummy)
Route::middleware(['auth','role:pelanggan'])
    ->group(function(){
        Route::get('/kk', fn() => 2)->name('home');
        Route::get('reservasi', fn() => 2)->name('reservasi.index');
        Route::get('reservasi/create', fn() => 2)->name('reservasi.create');
        Route::post('reservasi', fn() => 2)->name('reservasi.store');
    });
