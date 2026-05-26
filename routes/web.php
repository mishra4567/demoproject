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

Route::get('report/new', [ReportController::class, 'index'])
    ->name('report')->setDefaults(['label' => 'Report', 'role' => 1]);

Route::get('/test-mail', function () {
    \Illuminate\Support\Facades\Mail::raw('Test email from Laravel!', function ($message) {
        $message->to('your@email.com')
            ->subject('Test Mail');
    });
    return 'Mail sent!';
});


require __DIR__ . '/adminWeb.php';
require __DIR__ . '/api.php';
require __DIR__ . '/vendor.php';
