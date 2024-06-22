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
        Schema::create('branches', function (Blueprint $table) {
                     $table->id();
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('district_id')->nullable();
            $table->string('area')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('google_map_address');
            $table->double('lat', 15, 8);
            $table->double('lng', 15, 8);
            $table->time('open_time')->default('07:00:00');
            $table->time('close_time')->default('23:00:00');
            $table->enum('verification_status', ['verified', 'unverified']);
            $table->unsignedBigInteger('laundry_id')->onDelete('cascade');
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('district_id')->references('id')->on('districts')->nullable();
            $table->foreign('laundry_id')->references('id')->on('laundries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
