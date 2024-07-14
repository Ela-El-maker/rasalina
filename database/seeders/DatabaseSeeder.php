<?php

namespace Database\Seeders;

use App\Models\HomeSlide;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
        ]);
        HomeSlide::factory()->create([
           'title' => 'Welcome to Our Website',
    'sub_title' => 'We are glad to have you here',
    'home_slide' => 'uploads/home_slide/1.jpg',
    'video_url' => 'https://example.com/path-to-your-video.mp4',
        ]);
    }
}
