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
        Schema::create('laundries', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('arabic_name');
            $table->enum('type', ['laundry', 'central laundry']);
            $table->integer('branches');
            $table->string('tax_number');
            $table->string('commercial_registration_no');
            $table->string('commercial_registration_image');
            $table->string('logo')->nullable();
            $table->integer('is_central_laundry')->comment('0-> no,1->yes');
            $table->enum('subscription_status', ['inactive', 'active'])->default('inactive');
            $table->enum('verification_status', ['verified', 'unverified'])->default('unverified');
            $table->bigInteger('total_orders')->default(0);
             $table->double('rating', 3, 1)->default(0.0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            // // branch

            // $table->unsignedBigInteger('country_id');
            // $table->unsignedBigInteger('region_id');
            // $table->unsignedBigInteger('city_id');
            // $table->unsignedBigInteger('district_id')->nullable();
                        
            // $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            // $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            // $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            // $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
           
            // $table->string('area')->nullable();
            // $table->string('postal_code')->nullable();
            // $table->string('additional_address')->nullable();
// $table->time('open_time')->default('07:00:00');
//             $table->time('close_time')->default('23:00:00');
            // //branch location

            // $table->string('google_map_address');
            // $table->double('lat', 15, 8);
            // $table->double('lng', 15, 8);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laundries');
    }
};
