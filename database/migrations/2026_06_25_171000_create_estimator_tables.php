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
        // 1. Packages
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('min_rate', 12, 2);
            $table->decimal('max_rate', 12, 2);
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Rooms
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Add-ons
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('min_price', 12, 2);
            $table->decimal('max_price', 12, 2);
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 4. Estimator Settings (PDF Settings)
        Schema::create('estimator_settings', function (Blueprint $table) {
            $table->id();
            $table->string('pdf_title')->default('Home Interior Cost Estimate');
            $table->string('pdf_company_name')->default('Premium Touch');
            $table->string('pdf_address')->default('Dhaka, Bangladesh');
            $table->string('pdf_phone')->default('+880 1700-000000');
            $table->text('pdf_footer_text')->nullable();
            $table->text('pdf_terms_conditions')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 5. Estimator Leads
        Schema::create('estimator_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('location');
            $table->text('project_address')->nullable();
            $table->decimal('home_size', 12, 2);
            $table->string('flat_status'); // New Flat, Under Construction, Renovation
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->decimal('estimated_min', 12, 2);
            $table->decimal('estimated_max', 12, 2);
            $table->timestamps();
        });

        // 6. Estimator Room Items (quantity of rooms selected by lead)
        Schema::create('estimator_room_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estimator_lead_id')->constrained('estimator_leads')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamps();
        });

        // 7. Estimator Add-on Items (selected add-ons with quantities)
        Schema::create('estimator_addon_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estimator_lead_id')->constrained('estimator_leads')->cascadeOnDelete();
            $table->foreignId('addon_id')->constrained('addons')->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimator_addon_items');
        Schema::dropIfExists('estimator_room_items');
        Schema::dropIfExists('estimator_leads');
        Schema::dropIfExists('estimator_settings');
        Schema::dropIfExists('addons');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('packages');
    }
};
