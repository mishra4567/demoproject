<?php

namespace App\Providers;

use App\Helpers\RoleHelper;
use App\Models\CreateMediaTable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // For My Media Model to show media all over admin panal
        View::composer('admin.include.mediamodal', function ($view) {
            $media = CreateMediaTable::where('is_deleted', 0)
                ->where('is_vendor', 'ADMIN')
                // ✅ no status filter, no is_vendor filter — show everything non-deleted
                ->orderBy('created_at', 'desc')
                ->get();

            $view->with('media', $media);
        });
        View::composer('admin.component.upevent', function ($view) {
            $adminId = session('ADMIN_ID');
            // $limit = $view->getData()['limit'] ?? 9;

            $upcomingEvents = \App\Models\CalendarEvent::where('user_id', $adminId)
                ->where('start_time', '>=', now())
                ->orderBy('start_time', 'asc')
                // ->limit($limit)
                ->get();

            $view->with('upcomingEvents', $upcomingEvents);
        });
        Blade::component('admin.partials.not_found_page', 'not-found');
        // ✅ @role('module', 'action') — show if allowed
        Blade::if('role', function (string $module, string $action) {
            return RoleHelper::can($module, $action);
        });

        // ✅ @norole('module', 'action') — show if NOT allowed
        Blade::if('norole', function (string $module, string $action) {
            return RoleHelper::cannot($module, $action);
        });

        // ✅ @superadmin — show only for super admin
        Blade::if('superadmin', function () {
            return session('ADMIN_IS_SUPER') == 1;
        });
    }
}
