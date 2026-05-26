<?php
// routes/vendor.php
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Middleware\HandleVendorRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ── Vendor routes (new, open for now) ───────────────────────
Route::prefix('vendor')->middleware(['web',HandleVendorRequests::class])->group(function () {
    Route::get('/',function(){
        return Inertia::render('Dashboard');
    });
    // Route::resource('products', VendorProductController::class);
    // Route::resource('orders',   OrderController::class);
    // Route::resource('profile',  ProfileController::class);
});
