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
        Schema::create('handover_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('client');
            $table->string('image_path');
            $table->date('date')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handover_snapshots');
    }
};
