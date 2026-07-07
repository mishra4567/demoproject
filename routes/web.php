<?php

// use App\Http\Controllers\Website\ReportController;

use App\Http\Controllers\Website\ReportController;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});
Route::get('/api', function () {
    return response()->json(['message' => 'API is working']);
});
Route::get('/api-list', function () {

    $routes = collect(Route::getRoutes())
        ->filter(fn($route) => str_contains($route->uri(), 'api/'))
        ->map(fn($route) => [
            'method' => implode('|', $route->methods()),
            'uri' => url($route->uri()),
        ]);

    return response()->json([
        'status' => true,
        'routes' => $routes,
    ], 200, [], JSON_PRETTY_PRINT);
});

// ── Report routes (accessible by admin, vendor, customer) ────
// routes/web.php

// ── Report routes ────────────────────────────────────────────
Route::prefix('report')->name('report.')->group(function () {

    // Form — open to anyone, no auth required
    Route::get('/new',   [ReportController::class, 'createReport'])->name('create');
    Route::post('/store', [ReportController::class, 'store'])->name('store');
    Route::get('/show',   [ReportController::class, 'show'])->name('show');
});

Route::get('/test-mail', function () {
    \Illuminate\Support\Facades\Mail::raw('Test email from Laravel!', function ($message) {
        $message->to('your@email.com')
            ->subject('Test Mail');
    });
    return 'Mail sent!';
});
Route::get('/phpinfo', function () {
    phpinfo();
});

require __DIR__ . '/adminWeb.php';
require __DIR__ . '/api.php';
require __DIR__ . '/vendor.php';
