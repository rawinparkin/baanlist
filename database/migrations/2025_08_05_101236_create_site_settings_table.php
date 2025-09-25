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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('support_phone')->nullable();
            $table->text('company_address')->nullable();
            $table->text('company_address2')->nullable();
            $table->text('company_address3')->nullable();
            $table->text('company_lat')->nullable();
            $table->text('company_lon')->nullable();
            $table->string('line')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->text('terms_of_service')->nullable();
            $table->string('copyright')->nullable();
            $table->text('about_footer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
