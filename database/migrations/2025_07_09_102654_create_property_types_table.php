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
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_name');        // e.g., "Condo", "House", "Townhouse"
            $table->string('type_icon')->nullable(); // e.g., "fa-house", or image filename/path
            $table->string('slug');   // URL-friendly version of the name (e.g., "condo")
            $table->boolean('is_active')->default(true); // To enable/disable the type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_types');
    }
};