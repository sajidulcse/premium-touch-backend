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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->unique();
            $table->string('label');
            $table->string('type'); // text, email, tel, number, select, textarea
            $table->string('placeholder')->nullable();
            $table->text('options')->nullable(); // JSON string array for select type
            $table->boolean('is_required')->default(false);
            $table->boolean('is_enabled')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
