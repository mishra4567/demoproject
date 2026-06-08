<?php
// routes/vendor.php

use App\Http\Controllers\Vendor\AuthController;
use App\Http\Controllers\Vendor\VendorBrandController;
use App\Http\Controllers\Vendor\VendorCouponController;
use App\Http\Controllers\Vendor\VendorMediaController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Middleware\HandleVendorRequests;
use Illuminate\Http\Request;
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
            // Media search — reuse admin media
            Route::get('media/search', function (Request $request) {
                $query = $request->get('query', '');
                return \App\Models\CreateMediaTable::where('status', 1)
                    ->when(
                        $query,
                        fn($q, $query) =>
                        $q->where('tags', 'like', "%{$query}%")
                            ->orWhere('file_name', 'like', "%{$query}%")
                    )
                    ->get(['id', 'file_name', 'tags']);
            })->name('media.search');
            // Coupon routes
            Route::get('/coupons', [VendorCouponController::class, 'index'])->name('coupons.index');
            Route::post('/coupons/save', [VendorCouponController::class, 'save'])->name('coupons.manageprocess');
            Route::patch('coupons/{coupon}/status',  [VendorCouponController::class, 'status'])->name('coupons.status');      // ← add
            Route::delete('coupons/{coupon}',        [VendorCouponController::class, 'destroy'])->name('coupons.destroy');
            Route::patch('coupons/{coupon}/restore', [VendorCouponController::class, 'restore'])->name('coupons.restore');
            Route::delete('coupons/{coupon}/force',  [VendorCouponController::class, 'permanentDelete'])->name('coupons.force');
            Route::post('coupons/bulk',              [VendorCouponController::class, 'bulkAction'])->name('coupons.bulk');
            // Brand routes
            Route::get('brands',                   [VendorBrandController::class, 'index'])->name('brands.index');
            Route::post('brands/save',             [VendorBrandController::class, 'save'])->name('brands.save');
            Route::post('brands/bulk',             [VendorBrandController::class, 'bulkAction'])->name('brands.bulk');
            Route::patch('brands/{brand}/status',  [VendorBrandController::class, 'status'])->name('brands.status');
            Route::patch('brands/{brand}/restore', [VendorBrandController::class, 'restore'])->name('brands.restore');
            Route::delete('brands/{brand}',        [VendorBrandController::class, 'destroy'])->name('brands.destroy');
            Route::delete('brands/{brand}/force',  [VendorBrandController::class, 'permanentDelete'])->name('brands.force');
        });
    });
