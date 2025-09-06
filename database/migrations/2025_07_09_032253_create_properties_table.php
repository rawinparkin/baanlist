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
        Schema::create('properties', function (Blueprint $table) {

            $table->id();
            $table->string('property_code');
            $table->string('property_type_id');
            $table->string('user_id');
            $table->string('property_status');
            $table->string('property_built_year')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('status')->default(0); // Published/draft etc.
            $table->timestamps();
            $table->timestamps('expire_date')->nullable(); // ✅ New field
            $table->unsignedBigInteger('views')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
