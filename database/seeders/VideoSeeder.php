<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Video::count() > 0) {
            return;
        }

        Video::create([
            'title' => 'Bespoke Luxury Villa Walkthrough',
            'url' => 'https://www.youtube.com/watch?v=yYyKrxp5LwY',
            'description' => 'A cinematic look into our recently completed architectural marvel, detailing marble works and grand scale living.',
            'position' => 1
        ]);

        Video::create([
            'title' => 'Modern Minimalist Penthouse Tour',
            'url' => 'https://www.youtube.com/watch?v=52gT3XyO5Z8',
            'description' => 'Showcasing the custom lighting designs, premium veneers, and neutral tone palettes in our high-end penthouse layout.',
            'position' => 2
        ]);

        Video::create([
            'title' => 'Premium Contemporary Kitchen & Studio',
            'url' => 'https://www.youtube.com/watch?v=T_e4u22G9dI',
            'description' => 'An elegant walkthrough showcasing smart layouts, concealed storage, and integrated appliances.',
            'position' => 3
        ]);
    }
}
