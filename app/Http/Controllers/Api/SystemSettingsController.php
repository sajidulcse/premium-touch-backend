<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\SmsService;

class SystemSettingsController extends Controller
{
    /**
     * Get dynamic SMTP settings.
     */
    public function getMailSettings()
    {
        $settings = SystemSetting::whereIn('key', [
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name'
        ])->pluck('value', 'key')->toArray();

        // Default fallbacks matching the DB seed
        $keys = [
            'mail_host' => '127.0.0.1',
            'mail_port' => '2525',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'hello@example.com',
            'mail_from_name' => 'Premium Touch'
        ];

        foreach ($keys as $key => $default) {
            if (!isset($settings[$key])) {
                $settings[$key] = $default;
            }
        }

        // Mask the password for security
        if (!empty($settings['mail_password'])) {
            $settings['mail_password'] = '********';
        }

        return response()->json($settings);
    }

    /**
     * Update dynamic SMTP settings.
     */
    public function updateMailSettings(Request $request)
    {
        $data = $request->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        foreach ($data as $key => $value) {
            // If password is masked, do not update it in the database
            if ($key === 'mail_password' && $value === '********') {
                continue;
            }

            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        try {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'model_type' => 'SystemSetting',
                'model_id' => 0,
                'description' => 'Updated SMTP Configurations',
                'old_properties' => null,
                'new_properties' => array_merge($data, ['mail_password' => '********']),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            logger()->error("SMTP ActivityLog error: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'SMTP mail settings updated successfully.'
        ]);
    }

    /**
     * Get paginated audit logs (login activities).
     */
    public function getAuditLogs(Request $request)
    {
        $query = LoginActivity::query()->with('user');

        // Search IP, email, or user agent
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        // Filter by status (success/failed)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($logs);
    }

    /**
     * Get security insights and session logs.
     */
    public function getSecurityInsights(Request $request)
    {
        // 1. Clean up any orphaned personal access tokens (where the associated user model has been deleted)
        DB::table('personal_access_tokens')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereColumn('users.id', 'personal_access_tokens.tokenable_id');
            })->delete();

        // 2. Total active tokens count
        $activeSessionsCount = DB::table('personal_access_tokens')
            ->join('users', 'personal_access_tokens.tokenable_id', '=', 'users.id')
            ->count();

        // 3. Failed logins in past 24 hours
        $failedLogins24h = LoginActivity::where('status', 'failed')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        // 3. Current active sessions list
        $sessions = DB::table('personal_access_tokens')
            ->join('users', 'personal_access_tokens.tokenable_id', '=', 'users.id')
            ->select(
                'personal_access_tokens.id',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'personal_access_tokens.name as device_name',
                'personal_access_tokens.last_used_at',
                'personal_access_tokens.created_at'
            )
            ->orderBy('personal_access_tokens.last_used_at', 'desc')
            ->get();

        $currentTokenId = $request->user()->currentAccessToken()->id;

        // Fetch the latest successful login IP per user
        $userIps = DB::table('login_activities')
            ->where('status', 'success')
            ->select('user_id', DB::raw('MAX(id) as last_id'))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $ipDetails = DB::table('login_activities')
            ->whereIn('id', $userIps->pluck('last_id'))
            ->select('id', 'user_id', 'ip_address')
            ->get()
            ->keyBy('user_id');

        $formattedSessions = $sessions->map(function ($session) use ($currentTokenId, $ipDetails) {
            return [
                'id' => $session->id,
                'user_name' => $session->user_name,
                'user_email' => $session->user_email,
                'device_name' => $session->device_name,
                'last_used_at' => $session->last_used_at,
                'created_at' => $session->created_at,
                'is_current' => $session->id === $currentTokenId,
                'ip_address' => $ipDetails[$session->user_id]->ip_address ?? 'N/A',
            ];
        });

        return response()->json([
            'stats' => [
                'active_sessions_count' => $activeSessionsCount,
                'failed_logins_24h' => $failedLogins24h,
            ],
            'sessions' => $formattedSessions
        ]);
    }

    /**
     * Revoke active token (Force Logout device).
     */
    public function revokeToken(Request $request, $id)
    {
        $token = DB::table('personal_access_tokens')->where('id', $id)->first();

        if (!$token) {
            return response()->json(['message' => 'Active session not found.'], 404);
        }

        // Prevent admin from revoking their own current session through this endpoint
        // (they should use the logout endpoint instead for clean state reset)
        if ($token->id === $request->user()->currentAccessToken()->id) {
            return response()->json(['message' => 'You cannot revoke your active device session here. Please use the logout button.'], 400);
        }

        DB::table('personal_access_tokens')->where('id', $id)->delete();

        return response()->json([
            'message' => 'Device session revoked successfully. The user has been forced logged out.'
        ]);
    }

    /**
     * Get dynamic SMS settings and balance.
     */
    public function getSmsSettings(SmsService $smsService)
    {
        $settings = $smsService->getSettings();

        // Fetch dynamic balance
        $settings['balance'] = $smsService->getBalance();

        // Calculate count statistics for sent history
        $settings['sent_today'] = \App\Models\SmsLog::whereDate('created_at', \Carbon\Carbon::today())->count();
        $settings['sent_this_week'] = \App\Models\SmsLog::whereBetween('created_at', [
            \Carbon\Carbon::now()->startOfWeek(),
            \Carbon\Carbon::now()->endOfWeek()
        ])->count();
        $settings['sent_this_month'] = \App\Models\SmsLog::whereMonth('created_at', \Carbon\Carbon::now()->month)
                                                        ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                                        ->count();

        return response()->json($settings);
    }

    /**
     * Update dynamic SMS configurations.
     */
    public function updateSmsSettings(Request $request)
    {
        $data = $request->validate([
            'sms_enabled' => 'required|in:1,0,true,false',
            'sms_gateway_url' => 'required|url',
            'sms_api_key' => 'nullable|string',
            'sms_sender_id' => 'nullable|string',
            'sms_template_otp' => 'required|string',
            'sms_template_lead' => 'required|string',
        ]);

        foreach ($data as $key => $value) {
            // Do not update key if it is masked
            if ($key === 'sms_api_key' && $value === '********') {
                continue;
            }

            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        try {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'model_type' => 'SystemSetting',
                'model_id' => 0,
                'description' => 'Updated SMS Gateway Configurations',
                'old_properties' => null,
                'new_properties' => array_merge($data, ['sms_api_key' => '********']),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            logger()->error("SMS ActivityLog error: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'SMS Gateway configurations updated successfully.'
        ]);
    }

    /**
     * Send a test SMS.
     */
    public function sendTestSms(Request $request, SmsService $smsService)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string'
        ]);

        // Temporarily bypass the sms_enabled check specifically for test SMS to make setup verification easy
        $settings = $smsService->getSettings();
        $originalEnabled = SystemSetting::where('key', 'sms_enabled')->first()?->value ?? '0';

        SystemSetting::updateOrCreate(['key' => 'sms_enabled'], ['value' => '1']);

        try {
            $result = $smsService->sendSms($request->phone, $request->message);
        } finally {
            // Restore original enabled status
            SystemSetting::updateOrCreate(['key' => 'sms_enabled'], ['value' => $originalEnabled]);
        }

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Test SMS sent successfully!',
                'response' => $result['response']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'response' => $result['response']
        ], 400);
    }

    /**
     * Get paginated history of sent SMS logs.
     */
    public function getSmsHistory(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = \App\Models\SmsLog::latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('to', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('gateway', 'like', "%{$search}%");
            });
        }

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        $logs = $query->paginate(15);

        return response()->json($logs);
    }
}
