<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Room;
use App\Models\Addon;
use App\Models\EstimatorSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EstimatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Packages
        $packages = [
            [
                'name' => 'Basic',
                'description' => 'Elegant essential solutions focusing on functionality, durable materials, and standard finishes. Perfect for budget-conscious homeowners.',
                'display_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Standard',
                'description' => 'Highly customized modules, premium cabinetry, curated lighting layouts, and upgraded false ceiling designs. The sweet spot for modern city apartments.',
                'display_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Premium',
                'description' => 'Luxurious veneers, high-end stone countertops, automated lighting integration, wall paneling, and bespoke designer furniture. Crafted to turn heads.',
                'display_order' => 3,
                'status' => true,
            ],
            [
                'name' => 'Luxury',
                'description' => 'Fully bespoke masterpiece interiors featuring imported marble, smart-home automation, high-end Italian modular kitchens, and custom premium leather trims. Unlimited sophistication.',
                'display_order' => 4,
                'status' => true,
            ],
        ];

        $packageModels = [];
        foreach ($packages as $pkg) {
            $packageModels[$pkg['name']] = Package::create($pkg);
        }

        // 2. Seed Rooms
        $rooms = [
            ['name' => 'Living Room', 'slug' => 'living-room', 'status' => true],
            ['name' => 'Dining Room', 'slug' => 'dining-room', 'status' => true],
            ['name' => 'Kitchen', 'slug' => 'kitchen', 'status' => true],
            ['name' => 'Master Bedroom', 'slug' => 'master-bedroom', 'status' => true],
            ['name' => 'Bedroom', 'slug' => 'bedroom', 'status' => true],
            ['name' => 'Bathroom', 'slug' => 'bathroom', 'status' => true],
            ['name' => 'Balcony', 'slug' => 'balcony', 'status' => true],
            ['name' => 'Study Room', 'slug' => 'study-room', 'status' => true],
            ['name' => 'Kids Room', 'slug' => 'kids-room', 'status' => true],
            ['name' => 'Guest Room', 'slug' => 'guest-room', 'status' => true],
        ];

        $roomModels = [];
        foreach ($rooms as $r) {
            $roomModels[$r['slug']] = Room::create($r);
        }

        // Helper to seed Addon with package-specific prices
        $seedAddonWithPrices = function ($addonData, $roomId) use ($packageModels) {
            $minPrice = $addonData['min_price'];
            $maxPrice = $addonData['max_price'];

            // Create the addon
            $addon = Addon::create([
                'room_id' => $roomId,
                'name' => $addonData['name'],
                'description' => $addonData['description'] ?? null,
                'min_price' => null,
                'max_price' => null,
                'status' => true
            ]);

            // Calculate package prices
            $tiers = [
                'Basic' => $minPrice,
                'Standard' => round($minPrice + ($maxPrice - $minPrice) * 0.33, -2), // round to nearest 100
                'Premium' => round($minPrice + ($maxPrice - $minPrice) * 0.66, -2),
                'Luxury' => $maxPrice
            ];

            foreach ($tiers as $pkgName => $price) {
                if (isset($packageModels[$pkgName])) {
                    \App\Models\AddonPackagePrice::create([
                        'addon_id' => $addon->id,
                        'package_id' => $packageModels[$pkgName]->id,
                        'price' => $price
                    ]);
                }
            }
        };

        // 3. Seed Add-ons
        // Kitchen Add-ons
        $kitchenAddons = [
            ['name' => 'Modular Kitchen Cabinet', 'min_price' => 80000, 'max_price' => 180000, 'description' => 'Premium cabinet carcass with soft-close drawers and scratch-resistant acrylic/PU laminate.'],
            ['name' => 'Kitchen Accessories', 'min_price' => 15000, 'max_price' => 45000, 'description' => 'Stainless steel cutlery trays, pull-out baskets, bottle pull-outs, and corner tall-units.'],
            ['name' => 'Island Counter', 'min_price' => 25000, 'max_price' => 75000, 'description' => 'Independent center island with quartz or marble top, integrated under-storage, and breakfast counter.'],
            ['name' => 'Chimney / Hood', 'min_price' => 18000, 'max_price' => 60000, 'description' => 'Auto-clean high-suction chimney with touch controls, oil filter, and motion-sensor controls.'],
        ];
        foreach ($kitchenAddons as $addon) {
            $seedAddonWithPrices($addon, $roomModels['kitchen']->id);
        }

        // Bedroom Add-ons helper (apply to Master Bedroom, Bedroom, Kids Room, Guest Room)
        $bedroomSlugs = ['master-bedroom', 'bedroom', 'kids-room', 'guest-room'];
        $bedroomAddons = [
            ['name' => 'Wardrobe', 'min_price' => 45000, 'max_price' => 120000, 'description' => 'Standard floor-to-ceiling swing or sliding door wardrobe with inner drawers and premium fittings.'],
            ['name' => 'Dressing Unit', 'min_price' => 12000, 'max_price' => 35000, 'description' => 'Full-length luxury mirror paneling with dresser counter, soft-padded stool, and cosmetics shelves.'],
            ['name' => 'TV Unit', 'min_price' => 18000, 'max_price' => 45000, 'description' => 'Floating wall media console with backboard paneling, cable organizers, and ambient lighting slots.'],
        ];
        foreach ($bedroomSlugs as $slug) {
            if (isset($roomModels[$slug])) {
                foreach ($bedroomAddons as $addon) {
                    $seedAddonWithPrices($addon, $roomModels[$slug]->id);
                }
            }
        }

        // Living Room Add-ons
        $livingAddons = [
            ['name' => 'TV Console', 'min_price' => 25000, 'max_price' => 75000, 'description' => 'Bespoke floating media drawer unit with back panels (fluted, wooden laminates, etc.).'],
            ['name' => 'Feature Wall', 'min_price' => 30000, 'max_price' => 90000, 'description' => 'Designer main focus accent wall (louvers, wood panels, structural texture paints, or stone veneer).'],
            ['name' => 'False Ceiling', 'min_price' => 15000, 'max_price' => 50000, 'description' => 'Gypsum false ceiling framework with profile lighting, LED spots, and hidden wiring slots.'],
            ['name' => 'Decorative Lighting', 'min_price' => 10000, 'max_price' => 40000, 'description' => 'Pendant lights, smart LED strips, profile lights, and focal track spot-lights.'],
        ];
        foreach ($livingAddons as $addon) {
            $seedAddonWithPrices($addon, $roomModels['living-room']->id);
        }

        // Bathroom Add-ons
        $bathroomAddons = [
            ['name' => 'Vanity Unit', 'min_price' => 12000, 'max_price' => 35000, 'description' => 'Moisture-resistant HDMR vanity drawer under the washbasin with custom stone counter.'],
            ['name' => 'Mirror Cabinet', 'min_price' => 8000, 'max_price' => 22000, 'description' => 'Dual-purpose mirror cabinet with built-in storage shelves and soft-glow defogger LED strip.'],
        ];
        foreach ($bathroomAddons as $addon) {
            $seedAddonWithPrices($addon, $roomModels['bathroom']->id);
        }

        // Dining Room Add-ons
        $diningAddons = [
            ['name' => 'Crockery Cabinet', 'min_price' => 35000, 'max_price' => 85000, 'description' => 'Built-in wall-mount cabinetry with glass panel doors, inner LED spots, and drawers.'],
            ['name' => 'Dining Ceiling', 'min_price' => 12000, 'max_price' => 30000, 'description' => 'Minimal false ceiling drop directly above the dining table with hanging pendant slots.'],
        ];
        foreach ($diningAddons as $addon) {
            $seedAddonWithPrices($addon, $roomModels['dining-room']->id);
        }

        // Study Room Add-ons
        $studyAddons = [
            ['name' => 'Study Desk & Bookshelf', 'min_price' => 20000, 'max_price' => 60000, 'description' => 'Ergonomic study desk with keyboard tray, side drawer cabinet, and floating wall bookshelves.'],
        ];
        foreach ($studyAddons as $addon) {
            $seedAddonWithPrices($addon, $roomModels['study-room']->id);
        }

        // 4. Seed Settings
        EstimatorSetting::create([
            'pdf_title' => 'Home Interior Cost Estimate',
            'pdf_company_name' => 'Premium Touch Interior Studio',
            'pdf_address' => 'House 12, Road 4, Banani, Dhaka, Bangladesh',
            'pdf_phone' => '+880 1711-223344',
            'pdf_footer_text' => 'Thank you for choosing Premium Touch. We transform houses into premium homes.',
            'pdf_terms_conditions' => "1. This estimate is an approximation based on standard sizes and specifications. Actual pricing may vary after physical site measurement and design finalization.\n2. Prices are valid for 30 days from the date of generation.\n3. The package includes general interior design, carpentry work, standard painting, and basic electrical modification.\n4. Customized furniture, structural remodeling, and premium automation will be charged extra.",
            'logo' => null,
            'status' => true
        ]);
    }
}
