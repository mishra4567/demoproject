<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeWebsiteCrud extends Command
{
    protected $signature = 'make:website {name}';
    protected $description = 'Create Website CRUD';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        // Artisan::call("make:model {$name}");
        Artisan::call("make:controller Website/{$name}Controller");

        $this->info("Website CRUD created for {$name}");
    }
}
