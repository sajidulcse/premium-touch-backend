<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert initial marketing configurations from ENV fallbacks if they exist
        DB::table('system_settings')->insertOrIgnore([
            [
                'key' => 'analytics_enabled',
                'value' => env('ANALYTICS_ENABLED', '1'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'gtm_id',
                'value' => env('VITE_GTM_ID', ''),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'meta_pixel_id',
                'value' => env('VITE_META_PIXEL_ID', ''),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'meta_capi_access_token',
                'value' => env('META_CAPI_ACCESS_TOKEN', ''),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'meta_capi_test_event_code',
                'value' => env('META_CAPI_TEST_EVENT_CODE', ''),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'meta_capi_api_version',
                'value' => env('META_CAPI_API_VERSION', 'v19.0'),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('system_settings')->whereIn('key', [
            'analytics_enabled',
            'gtm_id',
            'meta_pixel_id',
            'meta_capi_access_token',
            'meta_capi_test_event_code',
            'meta_capi_api_version'
        ])->delete();
    }
};
