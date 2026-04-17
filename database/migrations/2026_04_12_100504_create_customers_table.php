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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // Contact
            $table->string('phone')->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('country')->default('India');

            // Business Info
            $table->string('company')->nullable();
            $table->string('gstin')->nullable();

            // Status & Auth
            $table->boolean('status')->default(1); // 1=active, 0=inactive
            $table->timestamp('email_verified_at')->nullable();

            // Extra
            $table->rememberToken(); // for login sessions
            $table->softDeletes();  // for safe delete

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
