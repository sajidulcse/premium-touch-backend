<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait HasImageUploads
{
    /**
     * Process and save an image as WebP.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory Directory relative to public storage or absolute path on disk
     * @param string|null $filename Custom filename (without extension). If null, a unique name will be generated.
     * @param int $maxWidth Max width to scale down (maintaining aspect ratio)
     * @param int $quality WebP compression quality
     * @param bool $isAbsolutePath Whether the $directory is an absolute path on disk
     * @return string Path/filename stored
     */
    protected function optimizeAndSaveImage(
        UploadedFile $file,
        string $directory,
        ?string $filename = null,
        int $maxWidth = 1920,
        int $quality = 80,
        bool $isAbsolutePath = false
    ): string {
        $manager = new ImageManager(new Driver());
        
        // Read file content using path/contents
        $img = $manager->read(file_get_contents($file->getPathname()));
        
        // Scale down if it exceeds max width
        if ($img->width() > $maxWidth) {
            $img->scale(width: $maxWidth);
        }
        
        // Convert to WebP format
        $encoded = $img->toWebp($quality);
        
        // Generate name
        $name = $filename ? Str::slug($filename) : uniqid();
        $nameWithExt = "{$name}.webp";
        
        if ($isAbsolutePath) {
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            file_put_contents(rtrim($directory, '/') . '/' . $nameWithExt, (string) $encoded);
            return $nameWithExt;
        } else {
            $path = rtrim($directory, '/') . '/' . $nameWithExt;
            Storage::disk('public')->put($path, (string) $encoded);
            return $path;
        }
    }
}
