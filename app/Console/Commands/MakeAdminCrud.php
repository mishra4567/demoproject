<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeAdminCrud extends Command
{
    protected $signature = 'make:admin {name}';
    protected $description = 'Create Admin CRUD';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        Artisan::call("make:model {$name} -m");
        Artisan::call("make:controller Admin/{$name}Controller");

        $this->info("Admin CRUD created for {$name}");
    }
}
