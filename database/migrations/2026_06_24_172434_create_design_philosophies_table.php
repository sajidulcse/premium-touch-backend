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
        Schema::create('design_philosophies', function (Blueprint $table) {
            $table->id();
            $table->string('step_number');
            $table->string('title');
            $table->string('image');
            $table->text('description');
            $table->timestamps();
        });

        DB::table('design_philosophies')->insert([
            [
                'step_number' => '01',
                'title' => 'Signature Craftsmanship',
                'image' => '/photo/values_step1.png',
                'description' => 'We implement custom millwork, detailed marble trims, and veneers to ensure every corner reflects luxury standards and tailored quality.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'step_number' => '02',
                'title' => 'Sustainable Elegance',
                'image' => '/photo/values_step2.png',
                'description' => 'Balancing premium visual styling with energy-efficient systems, eco-friendly materials, and smart automation for future-ready living.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'step_number' => '03',
                'title' => 'Bespoke Customization',
                'image' => '/photo/values_step3.png',
                'description' => 'No cookie-cutter templates. We design custom furniture layouts, curate bespoke palettes, and select art pieces to complement your tastes.',
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
        Schema::dropIfExists('design_philosophies');
    }
};
