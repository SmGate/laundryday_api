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
        Schema::create('services', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->string('service_name');
            $table->string('service_name_arabic');

            $table->text('service_description')->nullable(); // Allow longer text with nullable
            $table->text('service_description_arabic')->nullable(); // Allow longer text with nullable

            $table->string('service_image')->nullable(); // Image path or URL

            $table->decimal('delivery_fee', 8, 2); // Decimal for currency

            $table->decimal('operation_fee', 8, 2); // Decimal for currency

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
