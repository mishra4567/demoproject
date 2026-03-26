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
    }
}
