<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class RoutePathController extends Controller
{
    public function index()
    {
        return response()
            ->json([
                'status' => true,
                'data'   => config('route_paths'),
            ])
            // ->setEncodingOptions(JSON_PRETTY_PRINT);     
            // ->setEncodingOptions(JSON_UNESCAPED_SLASHES);
            ->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
