<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $query = Portfolio::where('status', 'published')
            ->with(['images', 'thumbnail', 'category', 'subCategory', 'childCategory']);

        if ($request->has('category') && $request->category !== 'all') {
            $slug = $request->category;
            $query->where(function ($q) use ($slug) {
                $q->whereHas('category', function ($q2) use ($slug) {
                    $q2->where('slug', $slug);
                })->orWhereHas('subCategory', function ($q2) use ($slug) {
                    $q2->where('slug', $slug);
                })->orWhereHas('childCategory', function ($q2) use ($slug) {
                    $q2->where('slug', $slug);
                });
            });
        }

        return response()->json($query->latest()->get());
    }

    public function adminIndex()
    {
        return response()->json(
            Portfolio::with(['images', 'thumbnail', 'category', 'subCategory', 'childCategory'])
                ->latest()
                ->get()
        );
    }

    public function show($slug)
    {
        $portfolio = Portfolio::with(['images', 'thumbnail', 'category', 'subCategory', 'childCategory'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($portfolio);
    }

    public function adminShow($id)
    {
        return response()->json(Portfolio::with(['images', 'thumbnail', 'category', 'subCategory', 'childCategory'])->findOrFail($id));
    }

    private function processAndSaveImage($file, $portfolioTitle, $index)
    {
        $manager = new ImageManager(new Driver());

        $slugName = Str::slug($portfolioTitle);
        $randomString = strtolower(Str::random(5));

        // SEO friendly descriptive file name
        $filename = "{$slugName}-interior-design-gallery-{$index}-{$randomString}.webp";
        $path = "portfolios/{$filename}";

        $img = $manager->read(file_get_contents($file->getPathname()));

        // Optimization for SEO and performance
        if ($img->width() > 1920) {
            $img->scale(width: 1920);
        }

        // Convert to WebP format for fast loading
        $encoded = $img->toWebp(80);
        Storage::disk('public')->put($path, (string) $encoded);

        // Generate descriptive alt text for SEO
        $altText = "{$portfolioTitle} interior design perspective " . ($index + 1);

        return [
            'path' => $path,
            'alt_text' => $altText
        ];
    }

    public function store(Request $request)
    {
        try {
            $toNull = ['category_id', 'sub_category_id', 'child_category_id'];
            foreach ($toNull as $field) {
                if ($request->has($field)) {
                    if ($request->input($field) === 'null' || $request->input($field) === '') {
                        $request->merge([$field => null]);
                    }
                }
            }

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'faqs' => 'nullable|string',
                'status' => 'required|in:published,draft',
                'category_id' => 'nullable|exists:categories,id',
                'sub_category_id' => 'nullable|exists:categories,id',
                'child_category_id' => 'nullable|exists:categories,id',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif,svg|max:10240'
            ]);

            $portfolio = Portfolio::create($request->only([
                'title',
                'description',
                'faqs',
                'status',
                'category_id',
                'sub_category_id',
                'child_category_id'
            ]));

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $index => $image) {
                    $imageData = $this->processAndSaveImage($image, $portfolio->title, $index);

                    PortfolioImage::create([
                        'portfolio_id' => $portfolio->id,
                        'image_path' => $imageData['path'],
                        'is_thumbnail' => ($index == 0),
                        'alt_text' => $imageData['alt_text']
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Portfolio created successfully',
                'portfolio' => $portfolio->load(['images', 'thumbnail', 'category'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save portfolio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $portfolio = Portfolio::findOrFail($id);

            $toNull = ['category_id', 'sub_category_id', 'child_category_id'];
            foreach ($toNull as $field) {
                if ($request->has($field)) {
                    if ($request->input($field) === 'null' || $request->input($field) === '') {
                        $request->merge([$field => null]);
                    }
                }
            }

            $request->validate([
                'title' => 'string|max:255',
                'description' => 'nullable|string',
                'faqs' => 'nullable|string',
                'status' => 'in:published,draft',
                'category_id' => 'nullable|exists:categories,id',
                'sub_category_id' => 'nullable|exists:categories,id',
                'child_category_id' => 'nullable|exists:categories,id',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif,svg|max:10240',
                'thumbnail_id' => 'nullable|exists:portfolio_images,id'
            ]);

            $portfolio->update($request->only([
                'title',
                'description',
                'faqs',
                'status',
                'category_id',
                'sub_category_id',
                'child_category_id'
            ]));

            if ($request->has('thumbnail_id')) {
                PortfolioImage::where('portfolio_id', $portfolio->id)->update(['is_thumbnail' => false]);
                PortfolioImage::where('id', $request->thumbnail_id)->update(['is_thumbnail' => true]);
            }

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $index => $image) {
                    $imageData = $this->processAndSaveImage($image, $portfolio->title, $index);

                    PortfolioImage::create([
                        'portfolio_id' => $portfolio->id,
                        'image_path' => $imageData['path'],
                        'is_thumbnail' => false,
                        'alt_text' => $imageData['alt_text']
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Portfolio updated successfully',
                'portfolio' => $portfolio->load(['images', 'thumbnail', 'category', 'subCategory', 'childCategory'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update portfolio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        foreach ($portfolio->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $portfolio->delete();
        return response()->json(['message' => 'Portfolio deleted successfully']);
    }

    public function deleteImage($id)
    {
        $image = PortfolioImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        if ($image->is_thumbnail) {
            $nextImage = PortfolioImage::where('portfolio_id', $image->portfolio_id)->first();
            if ($nextImage) {
                $nextImage->update(['is_thumbnail' => true]);
            }
        }

        return response()->json(['message' => 'Image deleted successfully']);
    }
}
