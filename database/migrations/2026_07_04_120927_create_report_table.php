<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('pgsql')
            ->create('reports', function ($table) {

                $table->id();

                $table->string('user_type');
                $table->bigInteger('user_id');
                $table->string('auth_email');
                $table->string('auth_password');
                $table->string('user_name')->nullable();

                $table->integer('rating')->default(0);
                $table->string('title')->nullable();

                $table->jsonb('report_data');

                $table->integer('status')->default(1);

                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report');
    }
};
