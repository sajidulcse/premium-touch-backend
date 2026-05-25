<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Clean HTML description to remove formatting newlines and whitespace.
     */
    private function cleanDescription(?string $html): ?string
    {
        if (!$html) return $html;
        $cleaned = preg_replace('/\r?\n|\r/', '', $html);
        $cleaned = preg_replace('/>\s+</', '><', $cleaned);
        $cleaned = preg_replace('/(<p><br><\/p>)+/', '<p><br></p>', $cleaned);
        $cleaned = preg_replace('/^<p><br><\/p>|<p><br><\/p>$/', '', $cleaned);
        return $cleaned;
    }

    public function index(Request $request)
    {
        $query = Service::where('status', 'published')
            ->with(['images', 'thumbnail', 'subCategory']);

        if ($request->has('category') && $request->category !== 'all') {
            $slug = $request->category;
            $query->whereHas('subCategory', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        return response()->json($query->latest()->get());
    }

    public function adminIndex()
    {
        return response()->json(
            Service::with(['images', 'thumbnail', 'subCategory'])
                ->latest()
                ->get()
        );
    }

    public function show($id)
    {
        $query = Service::where('status', 'published')->with(['images', 'thumbnail', 'subCategory']);

        if (is_numeric($id)) {
            $service = $query->findOrFail($id);
        } else {
            $service = $query->whereHas('subCategory', function ($q) use ($id) {
                $q->where('slug', $id);
            })->firstOrFail();
        }

        return response()->json($service);
    }

    public function adminShow($id)
    {
        $query = Service::with(['images', 'thumbnail', 'subCategory']);

        if (is_numeric($id)) {
            $service = $query->findOrFail($id);
        } else {
            $service = $query->whereHas('subCategory', function ($q) use ($id) {
                $q->where('slug', $id);
            })->firstOrFail();
        }

        return response()->json($service);
    }

    private function processAndSaveImage($file, $index)
    {
        $manager = new ImageManager(new Driver());

        $randomString = strtolower(Str::random(5));

        // SEO friendly descriptive file name
        $filename = "service-gallery-{$index}-{$randomString}.webp";
        $path = "services/gallery/{$filename}";

        $img = $manager->read(file_get_contents($file->getPathname()));

        // Optimization for SEO and performance
        if ($img->width() > 1920) {
            $img->scale(width: 1920);
        }

        // Convert to WebP format for fast loading
        $encoded = $img->toWebp(80);
        Storage::disk('public')->put($path, (string) $encoded);

        // Generate descriptive alt text for SEO
        $altText = "Service perspective " . ($index + 1);

        return [
            'path' => $path,
            'alt_text' => $altText
        ];
    }

    public function store(Request $request)
    {
        try {
            if ($request->has('sub_category_id')) {
                if ($request->input('sub_category_id') === 'null' || $request->input('sub_category_id') === '') {
                    $request->merge(['sub_category_id' => null]);
                }
            }

            $request->validate([
                'description' => 'required|string',
                'faqs' => 'nullable|string',
                'status' => 'required|in:published,draft',
                'sub_category_id' => 'required|exists:categories,id',
                'images' => 'required|array|min:1',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif,svg|max:10240'
            ]);

            $data = $request->only(['description', 'faqs', 'status', 'sub_category_id']);
            if (isset($data['description'])) {
                $data['description'] = $this->cleanDescription($data['description']);
            }

            $service = Service::create($data);

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $index => $image) {
                    $imageData = $this->processAndSaveImage($image, $index);

                    ServiceImage::create([
                        'service_id' => $service->id,
                        'image_path' => $imageData['path'],
                        'is_thumbnail' => ($index == 0),
                        'alt_text' => $imageData['alt_text']
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Service created successfully',
                'service' => $service->load(['images', 'thumbnail', 'subCategory'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save service: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $query = Service::query();
            if (is_numeric($id)) {
                $service = $query->findOrFail($id);
            } else {
                $service = $query->whereHas('subCategory', function ($q) use ($id) {
                    $q->where('slug', $id);
                })->firstOrFail();
            }

            if ($request->has('sub_category_id')) {
                if ($request->input('sub_category_id') === 'null' || $request->input('sub_category_id') === '') {
                    $request->merge(['sub_category_id' => null]);
                }
            }

            $request->validate([
                'description' => 'required|string',
                'faqs' => 'nullable|string',
                'status' => 'in:published,draft',
                'sub_category_id' => 'required|exists:categories,id',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif,svg|max:10240',
                'thumbnail_id' => 'nullable|exists:service_images,id'
            ]);

            $data = $request->only(['description', 'faqs', 'status', 'sub_category_id']);
            if (isset($data['description'])) {
                $data['description'] = $this->cleanDescription($data['description']);
            }

            $existingImagesCount = \App\Models\ServiceImage::where('service_id', $service->id)->count();
            $newImagesCount = $request->hasFile('images') ? count($request->file('images')) : 0;
            if ($existingImagesCount + $newImagesCount === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update service: At least one gallery image is required.'
                ], 422);
            }

            $service->update($data);

            if ($request->has('thumbnail_id')) {
                ServiceImage::where('service_id', $service->id)->update(['is_thumbnail' => false]);
                ServiceImage::where('id', $request->thumbnail_id)->update(['is_thumbnail' => true]);
            }

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $index => $image) {
                    $imageData = $this->processAndSaveImage($image, $index);

                    ServiceImage::create([
                        'service_id' => $service->id,
                        'image_path' => $imageData['path'],
                        'is_thumbnail' => false,
                        'alt_text' => $imageData['alt_text']
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Service updated successfully',
                'service' => $service->load(['images', 'thumbnail', 'subCategory'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update service: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        foreach ($service->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully']);
    }

    public function destroyImage($id)
    {
        $image = ServiceImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        if ($image->is_thumbnail) {
            $nextImage = ServiceImage::where('service_id', $image->service_id)->first();
            if ($nextImage) {
                $nextImage->update(['is_thumbnail' => true]);
            }
        }

        return response()->json(['message' => 'Image deleted successfully']);
    }
}