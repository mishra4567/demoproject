<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Run the database seeds.
         */
        DB::table('admins')->insert([
            ['name'=>'Root','email'=> 'gyhfvghfcvyttfyghfvgh@gmail.com','password'=> '$2y$12$Kbwqnve4EbG6Q6gilnmWYeg0wY25ojubU5xCW4jsyC8MtbNXWeS4m'],
        ]);
    }
}
