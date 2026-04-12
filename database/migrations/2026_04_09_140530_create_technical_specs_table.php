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
        Schema::create('technical_specs', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('lead_time_from');
            $table->string('lead_time_to');
            $table->string('tax');
            $table->string('tax_type');
            $table->boolean('is_promo')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_discounted')->default(0);
            $table->boolean('is_trending')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_specs');
    }
};
