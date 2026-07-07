<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->table('reports', function (Blueprint $table) {

            if (!Schema::connection('pgsql')->hasColumn('reports', 'auth_email')) {
                $table->string('auth_email')->nullable();
            }

            if (!Schema::connection('pgsql')->hasColumn('reports', 'auth_password')) {
                $table->string('auth_password')->nullable();
            }

            if (!Schema::connection('pgsql')->hasColumn('reports', 'user_name')) {
                $table->string('user_name')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->table('reports', function ($table) {
            $table->dropColumn(['auth_email', 'auth_password', 'user_name']);
        });
    }
};
