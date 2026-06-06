<?php
// routes/vendor.php

use App\Http\Controllers\Vendor\AuthController;
use App\Http\Controllers\Vendor\VendorCouponController;
use App\Http\Controllers\Vendor\VendorMediaController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Middleware\HandleVendorRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ── Vendor routes (new, open for now) ───────────────────────
Route::prefix('vendor')->name('vendor.')
    ->middleware(['web', HandleVendorRequests::class])->group(function () {
        Route::middleware('guest:vendor')->group(function () {
            Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
            Route::post('/register', [AuthController::class, 'register']);
            Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
            Route::post('/login',   [AuthController::class, 'login']);
        });
        Route::middleware('vendor.auth')->group(function () {
            Route::get('/', fn() => Inertia::render('Dashboard'))->name('dashboard');
            Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
            Route::get('/products', [VendorProductController::class, 'index'])->name('products.index');
            Route::get('/products/manageproduct/{id?}', [VendorProductController::class, 'manageproduct'])->name('product.manage');
            Route::post('/products/manageproductprocess', [VendorProductController::class, 'manageproductprocess'])->name('product.manageprocess');
            // media routes
            Route::get('/media', [VendorMediaController::class, 'mediaIndex'])->name('media.index');
            Route::get('/media/upload', [VendorMediaController::class, 'create'])->name('media.create');
            Route::post('/media/store', [VendorMediaController::class, 'store'])->name('media.store');
            // Coupon routes
            Route::get('/coupons', [VendorCouponController::class, 'index'])->name('coupons.index');
            Route::post('/coupons/save', [VendorCouponController::class, 'save'])->name('coupons.manageprocess');
            Route::delete('coupons/{coupon}', [VendorCouponController::class, 'destroy'])->name('coupons.destroy');
            Route::patch('coupons/{coupon}/restore', [VendorCouponController::class, 'restore'])->name('coupons.restore');
            Route::delete('coupons/{coupon}/force',  [VendorCouponController::class, 'permanentDelete'])->name('coupons.force');
            Route::post('coupons/bulk',              [VendorCouponController::class, 'bulkAction'])->name('coupons.bulk');


            // routes/vendor.php
            // Route::middleware('vendor.auth')->group(function () {
            //     Route::get('coupons',                    [CouponController::class, 'index'])->name('coupons.index');
            //     Route::post('coupons',                   [CouponController::class, 'store'])->name('coupons.store');
            //     Route::put('coupons/{coupon}',           [CouponController::class, 'update'])->name('coupons.update');
            //     Route::patch('coupons/{coupon}/status',  [CouponController::class, 'status'])->name('coupons.status');
            //     Route::patch('coupons/{coupon}/restore', [CouponController::class, 'restore'])->name('coupons.restore');
            //     Route::delete('coupons/{coupon}',        [CouponController::class, 'destroy'])->name('coupons.destroy');
            //     Route::delete('coupons/{coupon}/force',  [CouponController::class, 'permanentDelete'])->name('coupons.force');
            //     Route::post('coupons/bulk',              [CouponController::class, 'bulkAction'])->name('coupons.bulk');
            // });

        });
    });
