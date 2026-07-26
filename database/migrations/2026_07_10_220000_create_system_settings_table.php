<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed initial SMTP configs from env variables
        DB::table('system_settings')->insert([
            ['key' => 'mail_host', 'value' => env('MAIL_HOST', '127.0.0.1'), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mail_port', 'value' => env('MAIL_PORT', '2525'), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mail_username', 'value' => env('MAIL_USERNAME'), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mail_password', 'value' => env('MAIL_PASSWORD'), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mail_encryption', 'value' => env('MAIL_ENCRYPTION', 'tls'), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mail_from_address', 'value' => env('MAIL_FROM_ADDRESS', 'hello@example.com'), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mail_from_name', 'value' => env('MAIL_FROM_NAME', 'Premium Touch'), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
