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
        Schema::create('consultation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->string('project_type');
            $table->string('property_size'); // e.g. "1200 Sq Ft"
            $table->string('budget_range');
            $table->string('location');
            $table->text('notes')->nullable(); // "Tell Us About Your Project"
            $table->text('internal_notes')->nullable(); // Admin notes
            $table->string('status')->default('New'); // New → Contacted → Qualified → Closed
            $table->string('source'); // cta_modal, contact_page
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_requests');
    }
};
