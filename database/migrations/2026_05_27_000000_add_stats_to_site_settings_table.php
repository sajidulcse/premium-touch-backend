<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('stat_1_num')->nullable()->default('250+');
            $table->string('stat_1_label')->nullable()->default('Luxury Projects Completed');
            
            $table->string('stat_2_num')->nullable()->default('15+');
            $table->string('stat_2_label')->nullable()->default('Years of Design Experience');
            
            $table->string('stat_3_num')->nullable()->default('98%');
            $table->string('stat_3_label')->nullable()->default('Client Satisfaction Rate');
            
            $table->string('stat_4_num')->nullable()->default('18+');
            $table->string('stat_4_label')->nullable()->default('Design & Architecture Awards');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'stat_1_num', 'stat_1_label',
                'stat_2_num', 'stat_2_label',
                'stat_3_num', 'stat_3_label',
                'stat_4_num', 'stat_4_label'
            ]);
        });
    }
};
