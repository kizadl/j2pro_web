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
        Schema::create('home_menus', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // tv, radio, media
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('status')->default('offline'); // on_air, updated, offline
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_menus');
        //
    }
};
