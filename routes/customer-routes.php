<?php

use App\Http\Controllers\CustomerDashboardController;
use Illuminate\Support\Facades\Route;

// Customer Dashboard Routes
Route::middleware(['auth:pelanggan'])->prefix('customer')->name('customer.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Booking Management
    Route::get('/booking/create', [CustomerDashboardController::class, 'createBooking'])->name('booking.create');
    Route::post('/booking', [CustomerDashboardController::class, 'storeBooking'])->name('booking.store');
    Route::get('/booking/history', [CustomerDashboardController::class, 'bookingHistory'])->name('booking.history');
    // Tambahkan di route group customer
    Route::post('/booking/check-availability', [CustomerDashboardController::class, 'checkAvailability'])->name('booking.check-availability');

    // Cancel & Reschedule
    Route::post('/booking/{id}/cancel', [CustomerDashboardController::class, 'cancelBooking'])->name('booking.cancel');
    Route::post('/booking/{id}/reschedule', [CustomerDashboardController::class, 'rescheduleBooking'])->name('booking.reschedule');
    Route::post('/payment/update-status', [CustomerDashboardController::class, 'updatePaymentStatus'])->name('payment.update-status');
    // Reviews
    Route::post('/booking/{id}/review', [CustomerDashboardController::class, 'submitReview'])->name('booking.review');

    // Profile
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [CustomerDashboardController::class, 'updatePassword'])->name('password.update');


});
