<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Traits\HasImageUploads;

class ImageUploadsTest extends TestCase
{
    use HasImageUploads;

    public function test_it_saves_and_optimizes_image_to_webp()
    {
        Storage::fake('public');

        // Create a fake image (e.g. jpeg, 2000px width)
        $file = UploadedFile::fake()->image('test.jpg', 2000, 1000);

        // Run optimization
        $savedPath = $this->optimizeAndSaveImage($file, 'test_folder');

        // Assert file exists on public disk
        Storage::disk('public')->assertExists($savedPath);

        // Assert it is converted to webp
        $this->assertStringEndsWith('.webp', $savedPath);
    }
}
