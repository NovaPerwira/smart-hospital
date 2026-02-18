<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingResponseController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('bookings.create');
});

// ─── Patient Booking Routes ───────────────────────────────────────────────────
Route::prefix('bookings')->name('bookings.')->group(function () {
    Route::get('/create', [BookingController::class, 'create'])->name('create');
    Route::post('/store', [BookingController::class, 'store'])->name('store');
    Route::get('/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('confirmation');
    Route::get('/available-slots', [BookingController::class, 'getAvailableSlots'])->name('available-slots');

    // Phase 3 — Patient Response Handling (CTA links from H-1 reminder)
    Route::get('/confirm/{token}', [BookingResponseController::class, 'confirm'])->name('confirm');
    Route::get('/cancel/{token}', [BookingResponseController::class, 'cancel'])->name('cancel');
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [AdminController::class, 'bookingDetail'])->name('booking-detail');
    Route::post('/bookings/{booking}/arrived', [AdminController::class, 'markArrived'])->name('booking-arrived');
    Route::post('/bookings/{booking}/complete', [AdminController::class, 'markCompleted'])->name('booking-complete');
    Route::get('/follow-up-rules', [AdminController::class, 'followUpRules'])->name('follow-up-rules');
    Route::post('/follow-up-rules', [AdminController::class, 'storeFollowUpRule'])->name('follow-up-rules.store');
    Route::post('/follow-up-rules/{rule}/toggle', [AdminController::class, 'toggleFollowUpRule'])->name('follow-up-rules.toggle');
    Route::delete('/follow-up-rules/{rule}', [AdminController::class, 'destroyFollowUpRule'])->name('follow-up-rules.destroy');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/{report}', [AdminController::class, 'reportDetail'])->name('report-detail');
    Route::get('/notification-logs', [AdminController::class, 'notificationLogs'])->name('notification-logs');
    Route::get('/scheduled-follow-ups', [AdminController::class, 'scheduledFollowUps'])->name('scheduled-follow-ups');
});
