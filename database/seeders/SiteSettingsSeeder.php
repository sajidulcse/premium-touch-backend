<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (SiteSetting::count() === 0) {
            SiteSetting::create([
                'site_name' => "Premium Touch\nInterior Decor Studio",
                'tagline' => "Elegance & Comfort Redefined",
                'short_description' => "We are a boutique interior architecture and decor studio specializing in luxury residential and office spaces.",
                'about_page_description' => "Established in 2018, Premium Touch has designed over 150+ spaces.",
                'phone' => "+880 1711-223344",
                'email' => "info@premiumtouchbd.com",
                'career_email' => "career@premiumtouchbd.com",
                'address' => "House 24, Road 11, Banani, Dhaka, Bangladesh",
                'facebook_page_url' => "https://www.facebook.com/premiumtouch",
                'instagram_page_url' => "https://www.instagram.com/premiumtouch",
                'linkedin_page_url' => "https://www.linkedin.com/company/premiumtouch",
                'map_embed_url' => "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.056586348638!2d90.40428581504245!3d23.780963984574928!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c70c6d3df39b%3A0x8e8a719c264e10b4!2sBanani%20Graveyard%20Road!5e0!3m2!1sen!2sbd!4v1655000000000!5m2!1sen!2sbd",
                'map_url' => "https://maps.app.goo.gl/uX3Qd",
                'stat_1_num' => "150+",
                'stat_1_label' => "Projects Handed Over",
                'stat_2_num' => "98%",
                'stat_2_label' => "Client Satisfaction",
                'stat_3_num' => "5+",
                'stat_3_label' => "Years of Excellence",
                'stat_4_num' => "25+",
                'stat_4_label' => "Expert Designers"
            ]);
        }
    }
}
