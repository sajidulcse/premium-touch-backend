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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('instagram_page_url')->nullable()->after('facebook_page_url');
            $table->string('linkedin_page_url')->nullable()->after('instagram_page_url');
        });

        Schema::dropIfExists('social_links');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('url');
            $table->integer('position')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['instagram_page_url', 'linkedin_page_url']);
        });
    }
};
