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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();                        // Primary key
            $table->string('amenity_name');             // Amenity name (e.g., "Swimming Pool", "Gym")
            $table->string('amenity_icon')->nullable(); // Optional icon (Font Awesome class or image path)
            $table->boolean('is_active')->default(true); // Toggle to enable/disable in UI
            $table->timestamps();               // created_at / updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};