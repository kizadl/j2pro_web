<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tv_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tv_channel_id')->constrained('tv_channels')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('image_url')->nullable();
            $table->unsignedTinyInteger('day_index');
            $table->string('day_name', 20);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('start_minutes');
            $table->unsignedSmallInteger('end_minutes');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tv_channel_id', 'day_index', 'start_minutes']);
            $table->index(['day_index', 'start_minutes']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tv_schedules');
    }
};
