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
        Schema::create('servicetimings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();;
            $table->string('arabic_name')->nullable();;
            $table->text('description')->nullable();;
            $table->text('arabic_description')->nullable();;
            $table->integer('duration')->nullable();;
            $table->enum('type', ['min', 'second', 'hour'])->nullable();;
            $table->string('arabic_type')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicetimings');
    }
};
