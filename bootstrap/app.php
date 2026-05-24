<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'  => \App\Http\Middleware\RoleMiddleware::class,
            'admin_auth' => \App\Http\Middleware\AdminAuth::class,
        ]);
        $middleware->api(prepend:[
            HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated. Please login.'
                ], 401);
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Adding custom exception handler for 404 errors
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->is('admin/*')) {
                return response()->view('admin.partials.not_found_page', [
                    'type'    => '404',
                    'title'   => 'Page Not Found',
                    'message' => 'The page you are looking for does not exist.',
                    'btnText' => 'Go to Dashboard',
                    'btnUrl'  => url('admin/dashboard'),
                ], 404);
            }
        });
    })->create();
