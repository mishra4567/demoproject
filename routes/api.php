<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WishlistController;

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
            //  Wishlist
            Route::get('/wishlist',              [WishlistController::class, 'index']);
            Route::post('/wishlist/{product}',   [WishlistController::class, 'toggle']);
            Route::delete('/wishlist/{product}', [WishlistController::class, 'remove']);

            //  Cart
            Route::get('/cart',               [CartController::class, 'index']);
            Route::post('/cart',              [CartController::class, 'add']);
            Route::put('/cart/{product}',     [CartController::class, 'update']);
            Route::delete('/cart/{product}',  [CartController::class, 'remove']);
            Route::delete('/cart',            [CartController::class, 'clear']);
        });
    }
);
