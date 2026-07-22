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
        Schema::create('addon_package_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_id')->constrained('addons')->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->decimal('price', 12, 2);
            $table->timestamps();
            $table->unique(['addon_id', 'package_id']);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('min_rate', 12, 2)->nullable()->change();
            $table->decimal('max_rate', 12, 2)->nullable()->change();
        });

        Schema::table('addons', function (Blueprint $table) {
            $table->decimal('min_price', 12, 2)->nullable()->change();
            $table->decimal('max_price', 12, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->decimal('min_price', 12, 2)->nullable(false)->change();
            $table->decimal('max_price', 12, 2)->nullable(false)->change();
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('min_rate', 12, 2)->nullable(false)->change();
            $table->decimal('max_rate', 12, 2)->nullable(false)->change();
        });

        Schema::dropIfExists('addon_package_prices');
    }
};
