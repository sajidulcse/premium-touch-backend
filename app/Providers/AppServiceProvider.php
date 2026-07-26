<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $ip = $request->ip();
            $lockKey = 'login_lockout_' . md5($ip);

            // If strict lockout is active, return remaining seconds using a 0-attempt limiter
            if (\Illuminate\Support\Facades\Cache::has($lockKey)) {
                $seconds = \Illuminate\Support\Facades\Cache::get($lockKey) - time();
                if ($seconds > 0) {
                    return Limit::perMinute(0)->response(function () use ($seconds) {
                        return response()->json([
                            'message' => 'Too Many Attempts.',
                            'retry_after' => $seconds,
                            'is_blocked' => true
                        ], 429);
                    });
                }
            }

            // Check if standard rate limit key (md5('login' . ip)) is exceeded
            $laravelLimiterKey = md5('login' . $ip);
            if (RateLimiter::tooManyAttempts($laravelLimiterKey, 5)) {
                if (!\Illuminate\Support\Facades\Cache::has($lockKey)) {
                    \Illuminate\Support\Facades\Cache::put($lockKey, time() + 60, 60);
                }
                return Limit::perMinute(0)->response(function () {
                    return response()->json([
                        'message' => 'Too Many Attempts.',
                        'retry_after' => 60,
                        'is_blocked' => true
                    ], 429);
                });
            }

            return Limit::perMinute(5)->by($ip)->response(function (Request $request, array $headers) {
                return response()->json([
                    'message' => 'Too Many Attempts.',
                    'retry_after' => $headers['Retry-After'] ?? 60,
                    'is_blocked' => true
                ], 429, $headers);
            });
        });

        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Dynamic SMTP mail settings override
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
                $settings = \Illuminate\Support\Facades\DB::table('system_settings')
                    ->whereIn('key', [
                        'mail_host',
                        'mail_port',
                        'mail_username',
                        'mail_password',
                        'mail_encryption',
                        'mail_from_address',
                        'mail_from_name'
                    ])->pluck('value', 'key')->toArray();

                if (!empty($settings)) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $settings['mail_host'] ?? config('mail.mailers.smtp.host'),
                        'mail.mailers.smtp.port' => $settings['mail_port'] ?? config('mail.mailers.smtp.port'),
                        'mail.mailers.smtp.username' => $settings['mail_username'] ?? config('mail.mailers.smtp.username'),
                        'mail.mailers.smtp.password' => $settings['mail_password'] ?? config('mail.mailers.smtp.password'),
                        'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption'),
                        'mail.from.address' => $settings['mail_from_address'] ?? config('mail.from.address'),
                        'mail.from.name' => $settings['mail_from_name'] ?? config('mail.from.name'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Prevent failure during migrations or artisan commands
        }
    }
}
