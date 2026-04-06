<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RouteSearchController extends Controller
{
    //
    public function ajaxSearch(Request $request)
    {
        $keyword = strtolower($request->q);

        $routes = collect(Route::getRoutes())
            ->filter(function ($route) {
                return str_starts_with($route->uri(), 'admin')
                    && ($route->defaults['role'] ?? 0) === 0;
            })
            ->map(function ($route) {
                return [
                    'label' => $route->defaults['label']
                        ?? ucwords(str_replace(['.', '_'], ' ', $route->getName())),
                    'uri'   => url($route->uri()),
                ];
            })
            ->filter(
                fn($route) =>
                str_contains(strtolower($route['label']), $keyword)
            )
            ->values()
            ->take(8);

        return response()->json($routes);
    }
}
