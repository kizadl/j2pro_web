<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\TvChannel;
use App\Models\TvSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'administrator@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('p4ssw0rd321@'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        $channel = TvChannel::updateOrCreate(
            ['slug' => 'news-tv'],
            [
                'name' => 'NewsTV',
                'category' => 'News & Politics',
                'description' => '24/7 breaking news coverage',
                'source_type' => 'youtube',
                'youtube_id' => '9_ImDafOtrM',
                'watching_text' => '24K Watching',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ]
        );

        TvSchedule::updateOrCreate(
            [
                'tv_channel_id' => $channel->id,
                'day_index' => 1,
                'start_minutes' => 480,
            ],
            [
                'title' => 'Morning News Update',
                'description' => 'Latest breaking news and headlines',
                'day_name' => 'Monday',
                'start_time' => '08:00:00',
                'end_time' => '09:00:00',
                'end_minutes' => 540,
                'is_active' => true,
            ]
        );

        Banner::updateOrCreate(
            ['title' => 'Main Promo'],
            [
                'link_type' => 'none',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $this->call([
            HomeMenuSeeder::class,
        ]);
    }
}
