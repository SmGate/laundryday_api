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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->unique(['identifier', 'role',]);

            $table->string('identifier'); // 0510000000, suleman@gmail.com 
            $table->enum('provider', ['mobile_number', 'email']);
            $table->string('password')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('image')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('user_name')->unique()->nullable();

            $table->enum('role', ['customer', 'delivery_agent', 'admin', 'business_owner'])->default('customer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
