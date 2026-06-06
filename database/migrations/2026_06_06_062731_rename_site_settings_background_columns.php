<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->renameColumn('gallery_cta_bg', 'cta_bg');
            $table->renameColumn('project_header_bg', 'header_bg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->renameColumn('cta_bg', 'gallery_cta_bg');
            $table->renameColumn('header_bg', 'project_header_bg');
        });
    }
};
