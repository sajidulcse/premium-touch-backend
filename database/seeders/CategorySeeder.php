<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Category::count() === 0) {
            // 1. Services
            $services = Category::create([
                'name' => 'Services',
                'slug' => 'services',
                'parent_id' => 0,
                'position' => 1,
                'status' => true
            ]);

            Category::create([
                'name' => 'Residential Interior',
                'slug' => 'residential-interior',
                'parent_id' => $services->id,
                'position' => 1,
                'status' => true
            ]);

            Category::create([
                'name' => 'Commercial Office Design',
                'slug' => 'commercial-office',
                'parent_id' => $services->id,
                'position' => 2,
                'status' => true
            ]);

            Category::create([
                'name' => 'Home Renovation',
                'slug' => 'home-renovation',
                'parent_id' => $services->id,
                'position' => 3,
                'status' => true
            ]);

            // 2. Portfolio
            $portfolio = Category::create([
                'name' => 'Portfolio',
                'slug' => 'portfolio',
                'parent_id' => 0,
                'position' => 2,
                'status' => true
            ]);

            Category::create([
                'name' => 'Completed Projects',
                'slug' => 'completed-projects',
                'parent_id' => $portfolio->id,
                'position' => 1,
                'status' => true
            ]);

            // 3. Blogs
            $blogs = Category::create([
                'name' => 'Blogs',
                'slug' => 'blogs',
                'parent_id' => 0,
                'position' => 3,
                'status' => true
            ]);

            Category::create([
                'name' => 'Design Tips',
                'slug' => 'design-tips',
                'parent_id' => $blogs->id,
                'position' => 1,
                'status' => true
            ]);

            // 4. About Us
            Category::create([
                'name' => 'About Us',
                'slug' => 'about',
                'parent_id' => 0,
                'position' => 4,
                'status' => true
            ]);
            
            // 5. Contact
            Category::create([
                'name' => 'Contact',
                'slug' => 'contact',
                'parent_id' => 0,
                'position' => 5,
                'status' => true
            ]);
        }
    }
}
