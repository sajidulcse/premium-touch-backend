<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('footer_sections')) {
            Schema::create('footer_sections', function (Blueprint $table) {
                $table->id();
                $table->string('section_title');
                $table->string('section_type'); // e.g. text, links, social, newsletter
                $table->text('content')->nullable(); // Casted as array/json in model
                $table->integer('display_order')->default(0);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_sections');
    }
};
