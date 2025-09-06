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
        Schema::create('smtp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // e.g., "SendGrid Config"
            $table->string('mailer')->nullable(); // e.g., smtp, mailgun
            $table->string('host')->nullable();
            $table->string('port')->nullable(); // corrected from "post"
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('encryption')->nullable(); // e.g., tls, ssl
            $table->string('from_address')->nullable(); // e.g., support@yourapp.com
            $table->string('from_name')->nullable(); // e.g., "Your App Support"
            $table->boolean('active')->default(true); // for toggling
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtp_settings');
    }
};
