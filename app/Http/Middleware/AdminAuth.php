<?php

namespace App\Http\Middleware;

use App\Models\Admin;
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
        if (!$request->session()->has('ADMIN_LOGIN')) {
            // This store the intended url and go where wanted to go
            return redirect()->guest('admin')->with('denied', 'Access Denied. Please login first');
        }
        // Get current admin
        $admin = Admin::find(session('ADMIN_ID'));
        // Super admin bypass
        if ($admin->is_super_admin != 1) {

            // Rejected
            if ($admin->admin_appr == 2) {

                $request->session()->flush();

                return redirect('admin')
                    ->with('error', 'Your account has been suspended.');
            }

            // Suspended
            if ($admin->status != 1) {

                $request->session()->flush();

                return redirect('admin')
                    ->with('error', 'Your account is inactive.');
            }
        }
        return $next($request);
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
