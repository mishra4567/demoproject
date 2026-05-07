<?php

namespace App\Http\Middleware;

use App\Helpers\RoleHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        // Not logged in
        if (!session('ADMIN_LOGIN')) {
            return redirect()->route('admin.index')
                ->with('error', 'Please login first.');
        }
        // Super admin -bypass all
        if (session('ADMIN_IS_SUPER') == 1) {
            return $next($request);
        }
        // Check permission
        if (RoleHelper::cannot($module, $action)) {
            return response()->view('admin.partials.not_found_page', [
                'type'    => '404',
                'icon'    => 'fa-lock',
                'title'   => 'Access Restricted',
                'message' => 'You do not have permission to access this page.',
                // 'btnText' => 'Go to Dashboard',
                'btnUrl'  => url('admin/dashboard'),
            ]);
        }
        return $next($request);
    }
}
