<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeVendorCrud extends Command
{
    protected $signature = 'make:vendor {name}';
    protected $description = 'Create Vendor CRUD';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        // Artisan::call("make:model {$name}");
        Artisan::call("make:controller Vendor/{$name}Controller");

        $this->info("Vendor CRUD created for {$name}");
    }
}
