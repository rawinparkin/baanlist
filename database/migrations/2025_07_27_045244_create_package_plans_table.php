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
        Schema::create('package_plans', function (Blueprint $table) {
            $table->id();
            // Basic Info
            $table->string('package_name'); // e.g., "Extended"
            $table->string('package_cost')->nullable(); // e.g., 9.00
            $table->string('cost_desc')->nullable(); // e.g., "Extended"
            $table->decimal('price', 10, 2); // e.g., 9.00
            $table->unsignedInteger('package_credits')->default(1); // e.g., 1 listing

            // Duration & Type
            $table->enum('billing_type', ['one_time', 'monthly', 'yearly'])->default('yearly');
            $table->unsignedInteger('validity_days')->nullable(); // e.g., null for lifetime, or 30

            // Features
            $table->boolean('is_featured')->default(false); // Highlight in search
            $table->boolean('has_support')->default(true); // 24/7 Support or not
            $table->boolean('is_active')->default(true); // Highlight in search
            // Description (optional)
            $table->text('description')->nullable(); // e.g., "One time fee for one listing, highlighted..."

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_plans');
    }
};
