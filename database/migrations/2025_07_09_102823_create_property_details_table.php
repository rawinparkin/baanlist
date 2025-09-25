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
        Schema::create('property_details', function (Blueprint $table) {
            $table->id();
            $table->string('property_id');
            $table->string('bedrooms')->nullable();
            $table->string('bathrooms')->nullable();
            $table->string('land_size')->nullable();
            $table->string('usage_size')->nullable();
            $table->string('property_name');
            $table->string('property_slug')->nullable();
            $table->string('property_tag')->nullable();
            $table->text('long_descp')->nullable();
            $table->string('cover_photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_details');
    }
};
