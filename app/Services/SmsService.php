<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Retrieve SMS configurations from system_settings.
     */
    public function getSettings()
    {
        $settings = SystemSetting::whereIn('key', [
            'sms_enabled',
            'sms_gateway_url',
            'sms_api_key',
            'sms_sender_id',
            'sms_template_otp',
            'sms_template_lead'
        ])->pluck('value', 'key')->toArray();

        // Standard fallbacks / defaults
        $defaults = [
            'sms_enabled' => '0',
            'sms_gateway_url' => env('SMS_GATEWAY_URL', 'https://api.greenweb.com.bd/api.php'),
            'sms_api_key' => env('SMS_API_KEY', ''),
            'sms_sender_id' => env('SMS_SENDER_ID', ''),
            'sms_template_otp' => 'Your Premium Touch OTP is: {code}. Valid for 1 minute.',
            'sms_template_lead' => 'Premium Touch: A new lead has been submitted by {name}. Estimate: {cost} BDT.'
        ];

        foreach ($defaults as $key => $default) {
            if (!isset($settings[$key]) || $settings[$key] === '') {
                $settings[$key] = $default;
            }
        }

        return $settings;
    }

    /**
     * Determine if we should operate in Mock Mode (for development/fallback).
     */
    public function isMockMode($settings)
    {
        return empty($settings['sms_api_key']) || 
               str_contains(strtolower($settings['sms_gateway_url']), 'mock') || 
               app()->environment('testing');
    }

    /**
     * Send an SMS to a phone number.
     *
     * @param string $to
     * @param string $message
     * @return array
     */
    public function sendSms(string $to, string $message): array
    {
        $settings = $this->getSettings();

        // 1. Check if SMS is globally enabled
        if ($settings['sms_enabled'] !== '1' && $settings['sms_enabled'] !== 1 && $settings['sms_enabled'] !== 'true') {
            Log::info("SMS sending is disabled globally: to={$to}, message={$message}");
            return [
                'success' => false,
                'message' => 'SMS gateway is currently disabled in system settings.',
                'response' => 'disabled'
            ];
        }

        // Clean phone number format (typically remove non-digits, ensure standard prefix if required)
        $cleanPhone = preg_replace('/[^0-9]/', '', $to);
        
        // 2. Check Mock Mode
        if ($this->isMockMode($settings)) {
            Log::info("--- [SMS MOCK DRIVER] ---");
            Log::info("To: {$cleanPhone}");
            Log::info("Message: {$message}");
            Log::info("-------------------------");

            $this->logSms($cleanPhone, $message, 'SUCCESS', 'Mock');

            return [
                'success' => true,
                'message' => '[MOCK] SMS logged successfully.',
                'response' => 'mock_success_response_id_' . rand(100000, 999999)
            ];
        }

        // 3. Connect to live Gateway
        try {
            $url = $settings['sms_gateway_url'];
            $apiKey = $settings['sms_api_key'];
            $senderId = $settings['sms_sender_id'];

            // Greenweb BD or bdbulksms integration detection
            if (str_contains(strtolower($url), 'greenweb.com.bd') || str_contains(strtolower($url), 'bdbulksms')) {
                $params = [
                    'token' => $apiKey,
                    'to' => $cleanPhone,
                    'message' => $message,
                    'json' => 1
                ];
                if (!empty($senderId)) {
                    $params['senderid'] = $senderId;
                }
                
                Log::info("Sending SMS via Gateway to: {$cleanPhone}, message: {$message}");
                $response = Http::asForm()->post($url, $params);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("Gateway response: " . json_encode($data));
                    
                    // Greenweb/BDBulkSMS returns structure: [["status" => "SENT", "statusmsg" => "...", "smsid" => "..."]]
                    if (isset($data[0]['status']) && $data[0]['status'] === 'SENT') {
                        $this->logSms($cleanPhone, $message, 'SUCCESS', str_contains(strtolower($url), 'bdbulksms') ? 'BDBulkSMS' : 'Greenweb');
                        return [
                            'success' => true,
                            'message' => 'SMS sent successfully via Gateway.',
                            'response' => $data
                        ];
                    }
                    
                    $errorMsg = $data[0]['statusmsg'] ?? 'Failed sending via Gateway.';
                    $this->logSms($cleanPhone, $message, 'FAILED', str_contains(strtolower($url), 'bdbulksms') ? 'BDBulkSMS' : 'Greenweb', $errorMsg);
                    return [
                        'success' => false,
                        'message' => $errorMsg,
                        'response' => $data
                    ];
                }
            }
            // BulkSMSBD integration detection
            elseif (str_contains(strtolower($url), 'bulksmsbd.net')) {
                $response = Http::get($url, [
                    'api_key' => $apiKey,
                    'type' => 'text',
                    'number' => $cleanPhone,
                    'senderid' => $senderId,
                    'message' => $message
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['response_code']) && $data['response_code'] == 202) {
                        $this->logSms($cleanPhone, $message, 'SUCCESS', 'BulkSMSBD');
                        return [
                            'success' => true,
                            'message' => 'SMS sent successfully via BulkSMSBD.',
                            'response' => $data
                        ];
                    }
                    
                    $errorMsg = $data['success_message'] ?? 'Failed sending via BulkSMSBD.';
                    $this->logSms($cleanPhone, $message, 'FAILED', 'BulkSMSBD', $errorMsg);
                    return [
                        'success' => false,
                        'message' => $errorMsg,
                        'response' => $data
                    ];
                }
            }
            // Generic HTTP GET/POST implementation fallback
            else {
                // If it is a generic gateway, construct a standard request.
                // Replace placeholders in URL if any, or pass as query params.
                $response = Http::get($url, [
                    'api_key' => $apiKey,
                    'token' => $apiKey,
                    'to' => $cleanPhone,
                    'number' => $cleanPhone,
                    'sender' => $senderId,
                    'senderid' => $senderId,
                    'message' => $message,
                    'msg' => $message
                ]);

                if ($response->successful()) {
                    $this->logSms($cleanPhone, $message, 'SUCCESS', 'Generic');
                    return [
                        'success' => true,
                        'message' => 'SMS dispatched via generic gateway.',
                        'response' => $response->body()
                    ];
                }
            }

            $errorMsg = 'Failed to reach SMS Gateway. HTTP Status: ' . $response->status();
            $this->logSms($cleanPhone, $message, 'FAILED', 'Generic', $errorMsg);
            return [
                'success' => false,
                'message' => $errorMsg,
                'response' => $response->body()
            ];

        } catch (\Exception $e) {
            Log::error("SMS service error: " . $e->getMessage());
            $this->logSms($cleanPhone, $message, 'FAILED', 'Unknown', $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gateway Exception: ' . $e->getMessage(),
                'response' => null
            ];
        }
    }

    /**
     * Fetch the dynamic balance of the SMS API from gateway providers.
     *
     * @return string
     */
    public function getBalance(): string
    {
        $settings = $this->getSettings();

        // 1. Mock balance check
        if ($this->isMockMode($settings)) {
            return '124.50 BDT (~415 SMS)';
        }

        // 2. Fetch live balance
        try {
            $url = $settings['sms_gateway_url'];
            $apiKey = $settings['sms_api_key'];

            // Greenweb or bdbulksms balance endpoint check
            if (str_contains(strtolower($url), 'greenweb.com.bd') || str_contains(strtolower($url), 'bdbulksms')) {
                if (str_contains(strtolower($url), 'bdbulksms')) {
                    // Balance URL: https://api.bdbulksms.net/g_api.php?token=TOKEN&balance
                    $balanceUrl = 'https://api.bdbulksms.net/g_api.php';
                    $response = Http::get($balanceUrl, ['token' => $apiKey, 'balance' => '']);
                } else {
                    // Balance URL: https://api.greenweb.com.bd/gmasbalance.php?token=TOKEN
                    $balanceUrl = 'https://api.greenweb.com.bd/gmasbalance.php';
                    $response = Http::get($balanceUrl, ['token' => $apiKey]);
                }
                if ($response->successful()) {
                    $body = strip_tags(trim($response->body()));
                    if (is_numeric($body)) {
                        $val = floatval($body);
                        // Average SMS cost ~0.30 BDT
                        $smsCount = $val > 0 ? floor($val / 0.30) : 0;
                        return "{$body} BDT (~{$smsCount} SMS)";
                    }
                    return $body;
                }
            }
            // BulkSMSBD balance endpoint check
            elseif (str_contains(strtolower($url), 'bulksmsbd.net')) {
                // Balance URL: https://bulksmsbd.net/api/smsapi/balance?api_key=APIKEY
                $balanceUrl = 'https://bulksmsbd.net/api/smsapi/balance';
                $response = Http::get($balanceUrl, ['api_key' => $apiKey]);
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['balance'])) {
                        $body = strip_tags(trim($data['balance']));
                        if (is_numeric($body)) {
                            $val = floatval($body);
                            $smsCount = $val > 0 ? floor($val / 0.30) : 0;
                            return "{$body} BDT (~{$smsCount} SMS)";
                        }
                        return $body . ' BDT';
                    }
                }
            }
            // Generic or fallback balance lookup
            else {
                return 'Live Gateway connected (Dynamic balance check not supported for generic url)';
            }
        } catch (\Exception $e) {
            Log::warn("SMS balance fetch error: " . $e->getMessage());
        }

        return 'Failed to retrieve balance';
    }

    /**
     * Log SMS dispatch activity to the database sms_logs table.
     */
    private function logSms(string $to, string $message, string $status, string $gateway, ?string $errorMessage = null): void
    {
        try {
            \App\Models\SmsLog::create([
                'to' => $to,
                'message' => $message,
                'status' => $status,
                'gateway' => $gateway,
                'error_message' => $errorMessage
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to write to sms_logs table: " . $e->getMessage());
        }
    }
}
