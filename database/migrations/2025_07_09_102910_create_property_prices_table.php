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
        Schema::create('property_prices', function (Blueprint $table) {
            $table->id();
            $table->string('property_id');
            $table->unsignedBigInteger('sell_price')->nullable();
            $table->unsignedBigInteger('rent_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_prices');
    }
};
