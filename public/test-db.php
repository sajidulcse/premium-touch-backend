<?php
require __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Database Name: " . DB::connection()->getDatabaseName() . "\n";
echo "Services count: " . \App\Models\Service::count() . "\n";
echo "Projects count: " . \App\Models\Project::count() . "\n";
echo "Portfolios count: " . \App\Models\Portfolio::count() . "\n";
echo "Blogs count: " . \App\Models\Blog::count() . "\n";
echo "Comments count: " . \App\Models\Comment::count() . "\n";
echo "Categories count: " . \App\Models\Category::count() . "\n";
