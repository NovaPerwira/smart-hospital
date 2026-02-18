<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingResponseController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CmsController;
use Illuminate\Support\Facades\Route;

// ─── Landing Page ─────────────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/locale/{locale}', [LandingController::class, 'setLocale'])->name('locale.set');

// ─── Auth Routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'authenticate'])->name('login.post');
});
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ─── Patient Booking Routes ───────────────────────────────────────────────────
Route::prefix('bookings')->name('bookings.')->group(function () {
    Route::get('/create', [BookingController::class, 'create'])->name('create');
    Route::post('/store', [BookingController::class, 'store'])->name('store');
    Route::get('/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('confirmation');
    Route::get('/available-slots', [BookingController::class, 'getAvailableSlots'])->name('available-slots');
    Route::get('/lookup', [BookingController::class, 'lookup'])->name('lookup');

    // Phase 3 — Patient Response Handling (CTA links from H-1 reminder)
    Route::get('/confirm/{token}', [BookingResponseController::class, 'confirm'])->name('confirm');
    Route::get('/cancel/{token}', [BookingResponseController::class, 'cancel'])->name('cancel');
});


// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
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

    // ── CMS Routes ────────────────────────────────────────────────────────────
    Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
    Route::post('/cms/update', [CmsController::class, 'update'])->name('cms.update');
    Route::get('/cms/create', [CmsController::class, 'create'])->name('cms.create');
    Route::post('/cms/store', [CmsController::class, 'store'])->name('cms.store');
    Route::delete('/cms/{key}', [CmsController::class, 'destroy'])->name('cms.destroy');
});
