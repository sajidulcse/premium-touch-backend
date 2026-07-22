<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('title')->nullable();
                $table->string('slug')->nullable();
                $table->text('subtitle')->nullable();
                $table->string('short_description', 255)->nullable();
                $table->tinyInteger('status')->default(1);
                $table->integer('position')->default(0);
                $table->string('cover_image')->nullable();
                $table->json('content_blocks')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
