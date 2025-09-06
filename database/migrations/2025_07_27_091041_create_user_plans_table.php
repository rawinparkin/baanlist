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
        Schema::create('user_plans', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('paid_amount');
            $table->integer('credit');
            $table->unsignedBigInteger('billing_id');
            $table->string('invoice')->nullable();

            $table->dateTime('activated_at');
            $table->dateTime('expire_date');
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->boolean('is_renewable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_plans');
    }
};
