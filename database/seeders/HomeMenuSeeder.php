<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeMenu;

class HomeMenuSeeder extends Seeder
{
    public function run(): void
    {
        HomeMenu::updateOrCreate(
            ['key' => 'tv'],
            [
                'title' => 'TV Streaming',
                'subtitle' => 'Watch live TV channels',
                'status' => 'on_air',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        HomeMenu::updateOrCreate(
            ['key' => 'radio'],
            [
                'title' => 'Radio Streaming',
                'subtitle' => 'Listen live radio',
                'status' => 'updated',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        HomeMenu::updateOrCreate(
            ['key' => 'media'],
            [
                'title' => 'News & Media',
                'subtitle' => 'Latest news and updates',
                'status' => 'offline',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );
    }
}