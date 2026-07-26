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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->string('action'); // e.g. created, updated, deleted, login, logout
            $table->string('loggable_type')->nullable(); // For polymorphic relation
            $table->unsignedBigInteger('loggable_id')->nullable(); // For polymorphic relation
            $table->text('description'); // e.g. "Updated Blog: Title Here"
            $table->json('old_properties')->nullable(); // Old values before action
            $table->json('new_properties')->nullable(); // New values after action
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['loggable_type', 'loggable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
