<?php

namespace App\Providers;

use App\Models\CreateMediaTable;
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
            $media = CreateMediaTable::all();
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
    }
}
