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
        Schema::create('create_media_tables', function (Blueprint $table) {
            $table->id();

            $table->string('file_name');
            $table->string('media_type'); // jpg, png, mp4

            $table->string('tags')->nullable();
            $table->text('description')->nullable();

            $table->integer('vendor_id')->default(0);

            $table->integer('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_media_tables');
    }
};
