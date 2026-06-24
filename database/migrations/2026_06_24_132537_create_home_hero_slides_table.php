<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('home_hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('subtitle');
            $table->string('title');
            $table->text('desc');
            $table->text('image');
            $table->timestamps();
        });

        DB::table('home_hero_slides')->insert([
            [
                'subtitle' => 'PREMIUM TOUCH STUDIO',
                'title' => 'Curated Luxury Interiors',
                'desc' => 'Crafting bespoke residential spaces designed with premium materials, signature millwork, and luxury details.',
                'image' => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1920&q=80',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'subtitle' => 'ARCHITECTURAL PRECISION',
                'title' => 'Sophisticated Sanctuary',
                'desc' => 'Transforming physical layouts into highly personalized environments, blending modern utility with timeless elegance.',
                'image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1920&q=80',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'subtitle' => 'BESPOKE CRAFTSMANSHIP',
                'title' => 'Tailored Design Philosophy',
                'desc' => 'No cookie-cutter templates. We select custom marbles, rich veneers, and fine lighting to match your lifestyle.',
                'image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1920&q=80',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_hero_slides');
    }
};
