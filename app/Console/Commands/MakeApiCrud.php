<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeApiCrud extends Command
{
    protected $signature = 'make:api {name}';
    protected $description = 'Create API CRUD';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        // Artisan::call("make:model {$name}");
        Artisan::call("make:controller Api/{$name}Controller --api");

        $this->info("API CRUD created for {$name}");
    }
}
