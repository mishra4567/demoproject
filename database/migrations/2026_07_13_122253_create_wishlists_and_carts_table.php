<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Wishlist
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');        // ✅ plain column
            $table->unsignedBigInteger('product_id');
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
            $table->index('user_id');

            // ✅ Reference customers table instead of users
            $table->foreign('user_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
        });

        // Cart
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');        // ✅ plain column
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->json('options')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
            $table->index('user_id');

            // ✅ Reference customers table instead of users
            $table->foreign('user_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('carts');
    }
};
