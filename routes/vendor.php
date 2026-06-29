<?php
// routes/vendor.php

use App\Http\Controllers\Vendor\AuthController;
use App\Http\Controllers\Vendor\VendorBrandController;
use App\Http\Controllers\Vendor\VendorCategoryController;
use App\Http\Controllers\Vendor\VendorColorController;
use App\Http\Controllers\Vendor\VendorCouponController;
use App\Http\Controllers\Vendor\VendorLinkProductController;
use App\Http\Controllers\Vendor\VendorMediaController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorSizeController;
use App\Http\Controllers\Vendor\VendorTechnicalSpecsController;
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
            // Product routes
            Route::get('/products', [VendorProductController::class, 'index'])->name('products.index');
            Route::get('/products/manageproduct/{id?}',     [VendorProductController::class, 'manageproduct'])->name('product.manage');
            Route::post('/products/manageproductprocess',   [VendorProductController::class, 'manageproductprocess'])->name('product.manageprocess');
            Route::patch('products/{product}/status',         [VendorProductController::class, 'status'])->name('products.status');      // ← add
            Route::delete('products/{product}',               [VendorProductController::class, 'destroy'])->name('products.destroy');
            Route::patch('products/{product}/restore',        [VendorProductController::class, 'restore'])->name('products.restore');
            Route::delete('products/{product}/force',         [VendorProductController::class, 'permanentDelete'])->name('products.force');
            Route::post('products/bulk',                     [VendorProductController::class, 'bulk'])->name('products.bulk');
            // media routes
            Route::get('/media', [VendorMediaController::class, 'mediaIndex'])->name('media.index');
            Route::get('/media/upload', [VendorMediaController::class, 'create'])->name('media.create');
            Route::post('/media/store', [VendorMediaController::class, 'store'])->name('media.store');
            // Route::get('media/view/', [VendorMediaController::class, 'show'])->name('media.view');
            Route::get('media/search', [VendorMediaController::class, 'search'])->name('media.search');
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
            // Category routes
            Route::get('categories',                        [VendorCategoryController::class, 'index'])->name('categories.index');
            Route::post('categories/save',                  [VendorCategoryController::class, 'save'])->name('categories.save');
            Route::post('categories/bulk',                  [VendorCategoryController::class, 'bulk'])->name('categories.bulk');
            Route::patch('categories/{category}/status',    [VendorCategoryController::class, 'status'])->name('categories.status');
            Route::patch('categories/{category}/restore',   [VendorCategoryController::class, 'restore'])->name('categories.restore');
            Route::delete('categories/{category}',          [VendorCategoryController::class, 'destroy'])->name('categories.destroy');
            Route::delete('categories/{category}/force',    [VendorCategoryController::class, 'permanentDelete'])->name('categories.force');
            // Color routes
            Route::get('colors',                      [VendorColorController::class, 'index'])->name('colors.index');
            Route::post('colors/save',                [VendorColorController::class, 'save'])->name('colors.save');
            Route::post('colors/bulk',                [VendorColorController::class, 'bulk'])->name('colors.bulk');
            Route::patch('colors/{color}/status',     [VendorColorController::class, 'status'])->name('colors.status');
            Route::patch('colors/{color}/restore',    [VendorColorController::class, 'restore'])->name('colors.restore');
            Route::delete('colors/{color}',           [VendorColorController::class, 'destroy'])->name('colors.destroy');
            Route::delete('colors/{color}/force',     [VendorColorController::class, 'permanentDelete'])->name('colors.force');
            // Link-product routes
            Route::get('link-products',                           [VendorLinkProductController::class, 'index'])->name('link-products.index');
            Route::post('link-products/save',                     [VendorLinkProductController::class, 'save'])->name('link-products.save');
            Route::post('link-products/bulk',                     [VendorLinkProductController::class, 'bulk'])->name('link-products.bulk');
            Route::patch('link-products/{linkProduct}/status',    [VendorLinkProductController::class, 'status'])->name('link-products.status');
            Route::patch('link-products/{linkProduct}/restore',   [VendorLinkProductController::class, 'restore'])->name('link-products.restore');
            Route::delete('link-products/{linkProduct}',          [VendorLinkProductController::class, 'destroy'])->name('link-products.destroy');
            Route::delete('link-products/{linkProduct}/force',    [VendorLinkProductController::class, 'permanentDelete'])->name('link-products.force');
            Route::post('link-products/bulk-save',                [VendorLinkProductController::class, 'bulkSave'])->name('link-products.bulk-save');
            // Technical Specs routes
            Route::get('technical-specs',                             [VendorTechnicalSpecsController::class, 'index'])->name('technical-specs.index');
            Route::post('technical-specs/save',                       [VendorTechnicalSpecsController::class, 'save'])->name('technical-specs.save');
            Route::post('technical-specs/bulk',                       [VendorTechnicalSpecsController::class, 'bulk'])->name('technical-specs.bulk');
            Route::patch('technical-specs/{technicalSpec}/status',    [VendorTechnicalSpecsController::class, 'status'])->name('technical-specs.status');
            Route::patch('technical-specs/{technicalSpec}/restore',   [VendorTechnicalSpecsController::class, 'restore'])->name('technical-specs.restore');
            Route::delete('technical-specs/{technicalSpec}',          [VendorTechnicalSpecsController::class, 'destroy'])->name('technical-specs.destroy');
            Route::delete('technical-specs/{technicalSpec}/force',    [VendorTechnicalSpecsController::class, 'permanentDelete'])->name('technical-specs.force');
            // Sizes routes
            Route::get('sizes',                     [VendorSizeController::class, 'index'])->name('sizes.index');
            Route::post('sizes/save',               [VendorSizeController::class, 'save'])->name('sizes.save');
            Route::post('sizes/bulk',               [VendorSizeController::class, 'bulk'])->name('sizes.bulk');
            Route::patch('sizes/{size}/status',     [VendorSizeController::class, 'status'])->name('sizes.status');
            Route::patch('sizes/{size}/restore',    [VendorSizeController::class, 'restore'])->name('sizes.restore');
            Route::delete('sizes/{size}',           [VendorSizeController::class, 'destroy'])->name('sizes.destroy');
            Route::delete('sizes/{size}/force',     [VendorSizeController::class, 'permanentDelete'])->name('sizes.force');
        });
    });
