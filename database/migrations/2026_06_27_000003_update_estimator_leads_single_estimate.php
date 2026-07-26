<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Replaces estimated_min & estimated_max with a single total_estimate column.
     */
    public function up(): void
    {
        Schema::table('estimator_leads', function (Blueprint $table) {
            $table->decimal('total_estimate', 12, 2)->default(0)->after('package_id');
        });

        Schema::table('estimator_leads', function (Blueprint $table) {
            $table->dropColumn(['estimated_min', 'estimated_max']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estimator_leads', function (Blueprint $table) {
            $table->decimal('estimated_min', 12, 2)->default(0)->after('package_id');
            $table->decimal('estimated_max', 12, 2)->default(0)->after('estimated_min');
        });

        Schema::table('estimator_leads', function (Blueprint $table) {
            $table->dropColumn('total_estimate');
        });
    }
};
