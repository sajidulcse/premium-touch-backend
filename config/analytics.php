<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Analytics & Meta Conversions API (CAPI) Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for server-side Meta Conversions API (CAPI) dispatches.
    |
    */

    'enabled' => env('ANALYTICS_ENABLED', true),

    'meta' => [
        'pixel_id' => env('META_PIXEL_ID', ''),
        'access_token' => env('META_CAPI_ACCESS_TOKEN', ''),
        'test_event_code' => env('META_CAPI_TEST_EVENT_CODE', null),
        'api_version' => env('META_CAPI_API_VERSION', 'v19.0'),
    ],

];
