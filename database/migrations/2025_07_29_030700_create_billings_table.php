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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();

            // Card holder info (non-sensitive)
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('card_holder_name')->nullable(); // Optional

            // Billing address
            $table->string('address_line')->nullable();
            $table->string('sub_district')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->string('phone')->nullable();

            // Tokenized payment info (use if using Stripe or others)
            $table->string('payment_method_id')->nullable(); // e.g., Stripe's PM_xxx
            $table->string('brand')->nullable(); // Visa, MasterCard, etc.
            $table->string('last4')->nullable(); // Last 4 digits
            $table->string('exp_month')->nullable();
            $table->string('exp_year')->nullable();
            $table->unsignedBigInteger('amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
