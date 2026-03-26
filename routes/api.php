<?php

use App\Http\Controllers\Api\RoutePathController;
use Illuminate\Support\Facades\Route;




Route::get('api/paths', [RoutePathController::class, 'index']);
