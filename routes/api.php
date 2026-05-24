<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

// ─── Public Routes ─────────────────────────────────
Route::prefix('v1')->group(
    function () {

        // Auth
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login'])->name('login');
        // Products
        Route::get('/products',              [ProductController::class, 'index']);
        Route::get('products/{id}',         [ProductController::class, 'show']);
        // Route::get('products/featured',     [ProductController::class, 'featured']);
        // Route::get('categories',            [CategoryController::class, 'index']);
        // Route::get('categories/{id}/products', [ProductController::class, 'byCategory']);
        // Protected
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    }
);
