<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            if (! Schema::hasColumn('banners', 'subtitle')) {
                $table->string('subtitle')->nullable()->after('title');
            }

            if (! Schema::hasColumn('banners', 'is_live')) {
                $table->boolean('is_live')->default(false)->after('image_url');
            }

            if (! Schema::hasColumn('banners', 'link_type')) {
                $table->string('link_type')->default('none')->after('is_live');
            }

            if (! Schema::hasColumn('banners', 'link_value')) {
                $table->text('link_value')->nullable()->after('link_type');
            }

            if (! Schema::hasColumn('banners', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('link_value');
            }

            if (! Schema::hasColumn('banners', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('is_active');
            }

            if (! Schema::hasColumn('banners', 'start_at')) {
                $table->timestamp('start_at')->nullable()->after('sort_order');
            }

            if (! Schema::hasColumn('banners', 'end_at')) {
                $table->timestamp('end_at')->nullable()->after('start_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'subtitle',
                'is_live',
                'link_type',
                'link_value',
                'is_active',
                'sort_order',
                'start_at',
                'end_at',
            ]);
        });
    }
};