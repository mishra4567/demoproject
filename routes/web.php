<?php

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});






require __DIR__.'/adminWeb.php';
require __DIR__.'/api.php';
