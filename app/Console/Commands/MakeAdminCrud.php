<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeAdminCrud extends Command
{
    protected $signature = 'make:root {name}';
    protected $description = 'Create Root CRUD';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        Artisan::call("make:model {$name} -m");
        /**
         * I can't want to change the controller folder name Admin to Root becouse it is everywhere in the project and
         *  it will be hard to change it in all places, so I will keep it as Admin for now.
         */
        Artisan::call("make:controller Admin/{$name}Controller");

        $this->info("✅ Root CRUD created for {$name} Service:- Model, Migration, Controller");
    }
}
