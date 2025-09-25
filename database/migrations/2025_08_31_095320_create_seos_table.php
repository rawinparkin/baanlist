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
        Schema::create('seos', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('title1')->nullable();
            $table->string('title2')->nullable();
            $table->string('title3')->nullable();
            $table->text('adsense')->nullable();
            $table->text('adsense_headtag')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seos');
    }
};