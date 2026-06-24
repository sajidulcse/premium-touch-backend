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
        Schema::create('home_identities', function (Blueprint $table) {
            $table->id();
            $table->string('subtitle');
            $table->string('title');
            $table->text('description');
            $table->text('image');
            $table->timestamps();
        });

        DB::table('home_identities')->insert([
            [
                'subtitle' => 'OUR IDENTITY',
                'title' => 'Crafting Spaces, Defining Lifestyles',
                'description' => 'We believe that fine architecture and interior spaces are the physical expressions of personality. Our mission is to blend signature craftsmanship, premium marbles, and elegant warm wood veneers into functional, turnkey layout designs.',
                'image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1000&q=80',
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
        Schema::dropIfExists('home_identities');
    }
};
