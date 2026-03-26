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
        Schema::create('linkproducts', function (Blueprint $table) {
            $table->id();

            $table->string('sku', 100)->default('');
            $table->string('attr_image', 100)->default('');
            $table->integer('mrp')->default(0);
            $table->integer('price')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('size_id')->default(0);
            $table->integer('color_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('status')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linkproducts');
    }
};
