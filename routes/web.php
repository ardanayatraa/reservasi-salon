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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ShiftController;

// Public Routes
Route::get('/', [BookingController::class, 'index'])->name('landing-page');
Route::post('/book-service', [BookingController::class, 'bookService'])->name('book.service');
Route::post('/check-availability', [BookingController::class, 'checkAvailability'])->name('check.availability');
Route::get('/payment/finish', [BookingController::class, 'finish'])->name('payment.finish');

// Auth Routes
Route::post('/login', [CustomAuthenticatedSessionController::class, 'store']);
Route::post('/logout', [CustomAuthenticatedSessionController::class, 'destroy'])->name('logout');

// Admin Area
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/{admin}', [AdminController::class, 'show'])->name('admin.show');
    Route::get('/admin/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');

    // Pelanggan Routes
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');
    Route::get('/pelanggan/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');

    // Perawatan Routes
    Route::get('/perawatan', [PerawatanController::class, 'index'])->name('perawatan.index');
    Route::get('/perawatan/create', [PerawatanController::class, 'create'])->name('perawatan.create');
    Route::post('/perawatan', [PerawatanController::class, 'store'])->name('perawatan.store');
    Route::get('/perawatan/{perawatan}', [PerawatanController::class, 'show'])->name('perawatan.show');
    Route::get('/perawatan/{perawatan}/edit', [PerawatanController::class, 'edit'])->name('perawatan.edit');
    Route::put('/perawatan/{perawatan}', [PerawatanController::class, 'update'])->name('perawatan.update');
    Route::delete('/perawatan/{perawatan}', [PerawatanController::class, 'destroy'])->name('perawatan.destroy');

    // Pemesanan Routes
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');
    Route::get('/pemesanan/create', [PemesananController::class, 'create'])->name('pemesanan.create');
    Route::post('/pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('/pemesanan/{pemesanan}', [PemesananController::class, 'show'])->name('pemesanan.show');
    Route::get('/pemesanan/{pemesanan}/edit', [PemesananController::class, 'edit'])->name('pemesanan.edit');
    Route::put('/pemesanan/{pemesanan}', [PemesananController::class, 'update'])->name('pemesanan.update');
    Route::delete('/pemesanan/{pemesanan}', [PemesananController::class, 'destroy'])->name('pemesanan.destroy');

    // Pembayaran Routes
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/{pembayaran}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran/{pembayaran}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
    Route::put('/pembayaran/{pembayaran}', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::delete('/pembayaran/{pembayaran}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

    // Booked Routes
    Route::get('/booked', [BookedController::class, 'index'])->name('booked.index');
    Route::get('/booked/create', [BookedController::class, 'create'])->name('booked.create');
    Route::post('/booked', [BookedController::class, 'store'])->name('booked.store');
    Route::get('/booked/{booked}', [BookedController::class, 'show'])->name('booked.show');
    Route::get('/booked/{booked}/edit', [BookedController::class, 'edit'])->name('booked.edit');
    Route::put('/booked/{booked}', [BookedController::class, 'update'])->name('booked.update');
    Route::delete('/booked/{booked}', [BookedController::class, 'destroy'])->name('booked.destroy');

    // Karyawan Routes
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/karyawan/{karyawan}', [KaryawanController::class, 'show'])->name('karyawan.show');
    Route::get('/karyawan/{karyawan}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/karyawan/{karyawan}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{karyawan}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

    // Shift Routes
    Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
    Route::get('/shift/create', [ShiftController::class, 'create'])->name('shift.create');
    Route::post('/shift', [ShiftController::class, 'store'])->name('shift.store');
    Route::get('/shift/{shift}/edit', [ShiftController::class, 'edit'])->name('shift.edit');
    Route::put('/shift/{shift}', [ShiftController::class, 'update'])->name('shift.update');
    Route::delete('/shift/{shift}', [ShiftController::class, 'destroy'])->name('shift.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
});

// Pelanggan Area (Dummy)
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/kk', fn () => 2)->name('home');
    Route::get('/reservasi', fn () => 2)->name('reservasi.index');
    Route::get('/reservasi/create', fn () => 2)->name('reservasi.create');
    Route::post('/reservasi', fn () => 2)->name('reservasi.store');
});
