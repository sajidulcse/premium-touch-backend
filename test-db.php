<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// boot Laravel
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/plain');
echo "Database Name: " . DB::connection()->getDatabaseName() . "\n";
echo "Services count: " . \App\Models\Service::count() . "\n";
echo "Projects count: " . \App\Models\Project::count() . "\n";
echo "Portfolios count: " . \App\Models\Portfolio::count() . "\n";
echo "Blogs count: " . \App\Models\Blog::count() . "\n";
