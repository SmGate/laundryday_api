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

            $table->string('email')->nullable()->unique();
            $table->string('password')->unique();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->string('mobile_number')->nullable()->unique();

            $table->string('image')->nullable();


            $table->enum('role', ['customer', 'delivery_agent', 'admin', 'business_owner'])->default('customer');

            $table->string('username')->nullable()->unique();;

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
