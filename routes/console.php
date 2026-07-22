<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Prune audit and activity logs older than 90 days daily to prevent database bloat
\Illuminate\Support\Facades\Schedule::call(function () {
    \App\Models\ActivityLog::where('created_at', '<', now()->subDays(90))->delete();
    \App\Models\LoginActivity::where('created_at', '<', now()->subDays(90))->delete();
})->daily();
