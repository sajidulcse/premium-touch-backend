<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Replaces min_rate & max_rate with a single base_rate column.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // Add base_rate after the name column
            $table->decimal('base_rate', 12, 2)->default(0)->after('name');
        });

        // Drop old columns after adding the new one
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['min_rate', 'max_rate']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('min_rate', 12, 2)->default(0)->after('name');
            $table->decimal('max_rate', 12, 2)->default(0)->after('min_rate');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('base_rate');
        });
    }
};
