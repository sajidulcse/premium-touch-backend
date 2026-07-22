<?php

namespace App\Services\Analytics;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaCapiService
{
    /**
     * Sends a server-side conversion event to Meta Conversions API.
     *
     * @param string $eventName Standard Meta event (e.g. 'Lead', 'CustomizeProduct')
     * @param string|null $eventId Unique event ID matching client-side eventID for deduplication
     * @param array $userData User data array (email, phone, name, client_ip, user_agent, fbp, fbc)
     * @param array $customData Custom data properties (content_name, value, currency, etc.)
     * @param string|null $sourceUrl Page URL where event occurred
     * @return bool
     */
    public function sendEvent(
        string $eventName,
        ?string $eventId = null,
        array $userData = [],
        array $customData = [],
        ?string $sourceUrl = null
    ): bool {
        if (!config('analytics.enabled')) {
            Log::info('[Meta CAPI] Analytics disabled via config.');
            return false;
        }

        $pixelId = config('analytics.meta.pixel_id');
        $accessToken = config('analytics.meta.access_token');
        $apiVersion = config('analytics.meta.api_version', 'v19.0');
        $testEventCode = config('analytics.meta.test_event_code');

        if (empty($pixelId) || empty($accessToken)) {
            Log::warning('[Meta CAPI] Skipped event: META_PIXEL_ID or META_CAPI_ACCESS_TOKEN not configured.');
            return false;
        }

        // Prepare Hashed User Data for Meta Matching
        $formattedUserData = [];

        if (!empty($userData['email'])) {
            $formattedUserData['em'] = [hash('sha256', strtolower(trim($userData['email'])))];
        }

        if (!empty($userData['phone'])) {
            $cleanPhone = preg_replace('/[^\d]/', '', $userData['phone']);
            $formattedUserData['ph'] = [hash('sha256', $cleanPhone)];
        }

        if (!empty($userData['name'])) {
            $nameParts = explode(' ', trim($userData['name']), 2);
            if (!empty($nameParts[0])) {
                $formattedUserData['fn'] = [hash('sha256', strtolower(trim($nameParts[0])))];
            }
            if (!empty($nameParts[1])) {
                $formattedUserData['ln'] = [hash('sha256', strtolower(trim($nameParts[1])))];
            }
        }

        if (!empty($userData['client_ip'])) {
            $formattedUserData['client_ip_address'] = $userData['client_ip'];
        }

        if (!empty($userData['user_agent'])) {
            $formattedUserData['client_user_agent'] = $userData['user_agent'];
        }

        if (!empty($userData['fbp'])) {
            $formattedUserData['fbp'] = $userData['fbp'];
        }

        if (!empty($userData['fbc'])) {
            $formattedUserData['fbc'] = $userData['fbc'];
        }

        // Build Payload
        $eventData = [
            'event_name' => $eventName,
            'event_time' => time(),
            'action_source' => 'website',
            'user_data' => $formattedUserData,
            'custom_data' => (object) $customData,
        ];

        if ($eventId) {
            $eventData['event_id'] = $eventId;
        }

        if ($sourceUrl) {
            $eventData['event_source_url'] = $sourceUrl;
        }

        $body = [
            'data' => [$eventData],
        ];

        if (!empty($testEventCode)) {
            $body['test_event_code'] = $testEventCode;
        }

        $url = "https://graph.facebook.com/{$apiVersion}/{$pixelId}/events";

        try {
            $response = Http::timeout(5)
                ->asJson()
                ->post("{$url}?access_token={$accessToken}", $body);

            if ($response->successful()) {
                Log::info('[Meta CAPI] Event dispatched successfully.', [
                    'event_name' => $eventName,
                    'event_id' => $eventId,
                    'events_received' => $response->json('events_received', 0)
                ]);
                return true;
            } else {
                Log::error('[Meta CAPI] Graph API Error response:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Throwable $e) {
            Log::error('[Meta CAPI] Dispatch Exception: ' . $e->getMessage(), [
                'event_name' => $eventName,
                'event_id' => $eventId
            ]);
            return false;
        }
    }
}
