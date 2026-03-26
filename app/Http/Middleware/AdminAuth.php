<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('ADMIN_LOGIN')) {
            return $next($request);
        } else {
            // This store the intended url and go where wanted to go
            return redirect()->guest('admin')->with('denied', 'Access Denied. Please login first');
        }
    }
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if($request->session()->has('ADMIN_LOGIN')){
    //         return $next($request);
    //     }else{
    //         return redirect('admin')->with('denied','Access Denied');
    //     }
    // }
}
