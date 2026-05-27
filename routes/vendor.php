<?php
// routes/vendor.php

use App\Http\Controllers\Vendor\AuthController;
use App\Http\Middleware\HandleVendorRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ── Vendor routes (new, open for now) ───────────────────────
Route::prefix('vendor')->name('vendor.')
    ->middleware(['web', HandleVendorRequests::class])->group(function () {
        Route::middleware('guest:vendor')->group(function () {
            Route::get('register', [AuthController::class, 'showRegister'])->name('register');
            Route::post('register', [AuthController::class, 'register']);
            Route::get('login',    [AuthController::class, 'showLogin'])->name('login');
            Route::post('login',   [AuthController::class, 'login']);
        });
        Route::middleware('vendor.auth')->group(function () {
            Route::get('/', fn() => Inertia::render('Dashboard'))->name('dashboard');
            Route::post('logout',  [AuthController::class, 'logout'])->name('logout');
        });
    });
