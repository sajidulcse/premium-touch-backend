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
        Schema::create('client_reviews', function (Blueprint $table) {
            $table->id();
            $table->text('quote');
            $table->string('author');
            $table->string('location')->nullable();
            $table->timestamps();
        });

        DB::table('client_reviews')->insert([
            [
                'quote' => 'Premium Touch transformed our penthouse into a work of art. The attention to custom wood trims and marble finishes was absolute perfection.',
                'author' => 'Marcus & Sophia',
                'location' => 'Gulshan Residence',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'quote' => 'The spatial planning and smart lighting integrations they proposed maximized our room while delivering a world-class luxury feeling.',
                'author' => 'David Chen',
                'location' => 'Lakeside Villa Owner',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'quote' => 'A design team that truly listens. They took our conceptual brief and delivered a turnkey office that leaves our clients completely wowed.',
                'author' => 'Sarah Rahman',
                'location' => 'CEO, Nexa Studio',
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
        Schema::dropIfExists('client_reviews');
    }
};
