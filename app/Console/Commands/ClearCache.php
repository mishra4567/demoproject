<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearCache extends Command
{
    protected $signature = 'clear:cache';
    protected $description = 'Clear all application caches';

    public function handle()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');

        $this->info('All caches cleared successfully!');
    }
}
