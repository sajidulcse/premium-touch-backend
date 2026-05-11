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
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'subtitle')) {
                $table->text('subtitle')->nullable();
            }
            if (!Schema::hasColumn('services', 'cover_image')) {
                $table->string('cover_image')->nullable();
            }
            if (!Schema::hasColumn('services', 'content_blocks')) {
                $table->json('content_blocks')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'cover_image', 'content_blocks']);
        });
    }
};
