<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Traits\HasImageUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    use HasImageUploads;

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $ip = $request->ip();
        $userAgent = $request->userAgent();

        $user = User::where('email', $request->email)->first();

        // 1. Check if user exists
        if (!$user) {
            LoginActivity::create([
                'user_id' => null,
                'email' => $request->email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'status' => 'failed',
            ]);
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        // 2. Check if user is active
        if (!$user->is_active) {
            LoginActivity::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'status' => 'failed',
            ]);
            return response()->json(['message' => 'Your account is deactivated. Please contact the administrator.'], 403);
        }

        // 3. Verify password
        if (!Hash::check($request->password, $user->password)) {
            LoginActivity::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'status' => 'failed',
            ]);
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        // 4. Successful login
        // Allow multiple concurrent sessions to prevent attacker-led session hijacking lockout.
        // $user->tokens()->delete();

        // 4.1 Check email verification status
        if (is_null($user->email_verified_at)) {
            $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $user->verification_code = Hash::make($code);
            $user->verification_code_expires_at = now()->addMinutes(10);
            $user->save();

            Log::info("Email verification requested for {$user->email}. Verification OTP: {$code}");

            try {
                \Illuminate\Support\Facades\Mail::raw("Your email verification code is: {$code}", function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Email Verification Code');
                });
            } catch (\Exception $e) {
                Log::error("Failed to send email verification to {$user->email}: " . $e->getMessage());
            }

            // Log pending verification activity
            LoginActivity::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'status' => 'failed', // pending
            ]);

            return response()->json([
                'message' => 'Email verification required. A code has been sent to your email.',
                'requires_verification' => true,
                'email' => $user->email
            ], 403);
        }

        // Detect device type from User-Agent for meaningful Session Identifier
        $ua = strtolower($userAgent ?? '');
        if (str_contains($ua, 'iphone') || str_contains($ua, 'ipad')) {
            $deviceName = 'iOS Device';
        } elseif (str_contains($ua, 'android')) {
            $deviceName = 'Android Device';
        } elseif (str_contains($ua, 'postman')) {
            $deviceName = 'Postman Client';
        } elseif (str_contains($ua, 'insomnia')) {
            $deviceName = 'Insomnia Client';
        } elseif (str_contains($ua, 'curl')) {
            $deviceName = 'cURL Client';
        } elseif (str_contains($ua, 'mozilla') || str_contains($ua, 'chrome') || str_contains($ua, 'safari') || str_contains($ua, 'firefox') || str_contains($ua, 'edge')) {
            $deviceName = 'Web Browser';
        } else {
            $deviceName = $request->input('device_name', 'Unknown Client');
        }

        // Dynamic token expiry: 30 days for Remember Me, 24 hours otherwise
        $expiresAt = $request->boolean('remember_me')
            ? now()->addDays(30)
            : now()->addHours(24);

        $token = $user->createToken($deviceName, ['*'], $expiresAt)->plainTextToken;

        $user->last_login_at = now();
        $user->save();

        LoginActivity::create([
            'user_id' => $user->id,
            'email' => $request->email,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'status' => 'success',
        ]);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $user->profile_picture,
                'is_active' => $user->is_active,
                'last_login_at' => $user->last_login_at ? $user->last_login_at->toIso8601String() : null,
                'role' => $user->getRoleNames()->first() ?? 'No Role',
            ],
            'role' => $user->getRoleNames()->first() ?? 'No Role',
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray()
        ]);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get admin profile.
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_picture' => $user->profile_picture,
            'is_active' => $user->is_active,
            'last_login_at' => $user->last_login_at ? $user->last_login_at->toIso8601String() : null,
            'role' => $user->getRoleNames()->first() ?? 'No Role',
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray()
        ]);
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $this->optimizeAndSaveImage($request->file('profile_picture'), 'profiles');
            $user->profile_picture = $path;
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $user->profile_picture,
                'is_active' => $user->is_active,
                'last_login_at' => $user->last_login_at ? $user->last_login_at->toIso8601String() : null,
            ],
            'role' => $user->getRoleNames()->first() ?? 'No Role',
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray()
        ]);
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'The current password you entered is incorrect.'], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $ip = $request->ip();
        $countKey = 'otp-request-count:' . $ip;
        $blockKey = 'otp-request-block:' . $ip;

        // Check if currently blocked
        if (Cache::has($blockKey)) {
            $expiresAt = Cache::get($blockKey);
            $seconds = $expiresAt - time();
            if ($seconds > 0) {
                return response()->json([
                    'message' => 'Too Many Attempts.',
                    'retry_after' => $seconds,
                    'is_blocked' => true
                ], 429);
            }
        }

        // Get current attempts count
        $attempts = Cache::get($countKey, 0);

        if ($attempts >= 3) {
            // Set block for 180 seconds (3 minutes)
            $expiresAt = time() + 180;
            Cache::put($blockKey, $expiresAt, 180);
            // Reset count
            Cache::forget($countKey);

            return response()->json([
                'message' => 'Too Many Attempts.',
                'retry_after' => 180,
                'is_blocked' => true
            ], 429);
        }

        // Increment attempts count (expires in 5 minutes if no block is triggered)
        Cache::put($countKey, $attempts + 1, 300);

        $token = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        Log::info("Password reset requested for {$request->email}. Verification Pin: {$token}");

        try {
            \Illuminate\Support\Facades\Mail::raw("Your password reset verification code is: {$token}", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Password Reset Verification Code');
            });
        } catch (\Exception $e) {
            Log::error("Failed to send password reset email to {$request->email}: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'Verification code sent. Please check your email inbox.'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return response()->json(['message' => 'Invalid or expired verification code.'], 422);
        }

        if (now()->subMinutes(2) > $reset->created_at) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json(['message' => 'Verification code has expired.'], 422);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        // Revoke all Sanctum tokens on password reset
        $user->tokens()->delete();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password has been reset successfully.'
        ]);
    }

    /**
     * Verify email address using the 6-digit code.
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if (is_null($user->verification_code) || is_null($user->verification_code_expires_at)) {
            return response()->json(['message' => 'No active verification request found.'], 422);
        }

        if (now() > $user->verification_code_expires_at) {
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();
            return response()->json(['message' => 'Verification code has expired.'], 422);
        }

        if (!Hash::check($request->code, $user->verification_code)) {
            return response()->json(['message' => 'Invalid verification code.'], 422);
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->last_login_at = now();
        $user->save();

        $token = $user->createToken('admin-token')->plainTextToken;

        // Log successful activity
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');
        \App\Models\LoginActivity::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'status' => 'success',
        ]);

        return response()->json([
            'message' => 'Email verified and login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $user->profile_picture,
                'is_active' => $user->is_active,
                'last_login_at' => $user->last_login_at ? $user->last_login_at->toIso8601String() : null,
                'role' => $user->getRoleNames()->first() ?? 'No Role',
            ],
            'role' => $user->getRoleNames()->first() ?? 'No Role',
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray()
        ]);
    }

    /**
     * Resend verification code.
     */
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if (!is_null($user->email_verified_at)) {
            return response()->json(['message' => 'Email is already verified.'], 422);
        }

        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $user->verification_code = Hash::make($code);
        $user->verification_code_expires_at = now()->addMinutes(10);
        $user->save();

        Log::info("Email verification resent for {$user->email}. Verification OTP: {$code}");

        try {
            \Illuminate\Support\Facades\Mail::raw("Your email verification code is: {$code}", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Email Verification Code');
            });
        } catch (\Exception $e) {
            Log::error("Failed to resend email verification to {$user->email}: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'A new verification code has been sent to your email.'
        ]);
    }
}
