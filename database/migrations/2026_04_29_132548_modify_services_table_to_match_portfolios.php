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
            $table->dropColumn([
                'subtitle',
                'short_description',
                'status',
                'position',
                'cover_image',
                'content_blocks'
            ]);

            $table->string('title')->after('id');
            $table->string('slug')->unique()->after('title');
            $table->text('description')->nullable()->after('slug');
            $table->json('faqs')->nullable()->after('description');
            
            $table->unsignedBigInteger('sub_category_id')->nullable()->after('category_id');
            $table->unsignedBigInteger('child_category_id')->nullable()->after('sub_category_id');
            
            $table->string('status')->default('published')->after('child_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'slug',
                'description',
                'faqs',
                'sub_category_id',
                'child_category_id',
                'status'
            ]);

            $table->text('subtitle')->nullable();
            $table->string('short_description', 255)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('position')->default(0);
            $table->string('cover_image')->nullable();
            $table->json('content_blocks')->nullable();
        });
    }
};
