<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FooterSection;

class FooterSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (FooterSection::count() === 0) {
            FooterSection::create([
                'section_title' => 'About Premium Touch',
                'section_type' => 'text',
                'content' => [
                    'text' => 'Premium Touch is a leading interior design and decor studio in Dhaka, creating elegant, high-end residential and commercial spaces.'
                ],
                'display_order' => 1,
                'status' => true
            ]);

            FooterSection::create([
                'section_title' => 'Useful Links',
                'section_type' => 'links',
                'content' => [
                    ['label' => 'Home', 'url' => '/'],
                    ['label' => 'About Us', 'url' => '/about'],
                    ['label' => 'Our Services', 'url' => '/services'],
                    ['label' => 'Portfolio', 'url' => '/portfolio'],
                    ['label' => 'Cost Calculator', 'url' => '/estimator'],
                ],
                'display_order' => 2,
                'status' => true
            ]);

            FooterSection::create([
                'section_title' => 'Connect With Us',
                'section_type' => 'social',
                'content' => [
                    'facebook' => 'https://facebook.com/premiumtouch',
                    'instagram' => 'https://instagram.com/premiumtouch',
                    'linkedin' => 'https://linkedin.com/company/premiumtouch'
                ],
                'display_order' => 3,
                'status' => true
            ]);
        }
    }
}
