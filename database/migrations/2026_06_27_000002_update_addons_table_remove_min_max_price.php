<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Removes min_price & max_price from addons table (pricing managed via addon_package_prices).
     */
    public function up(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->dropColumn(['min_price', 'max_price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->decimal('min_price', 12, 2)->default(0)->after('name');
            $table->decimal('max_price', 12, 2)->default(0)->after('min_price');
        });
    }
};
