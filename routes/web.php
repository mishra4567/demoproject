<?php

use App\Http\Controllers\Website\ReportController;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
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
