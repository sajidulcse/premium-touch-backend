<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeHeroSlide;
use Illuminate\Http\Request;

class HomeHeroSlideController extends Controller
{
    public function index()
    {
        return response()->json(HomeHeroSlide::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'subtitle' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        if (!$request->hasFile('image_file') && !$request->filled('image')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed: Either an image file upload or an image URL is required.'
            ], 422);
        }

        $imagePath = $request->input('image') ?: '';

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/hero'), $fileName);
            $imagePath = 'hero/' . $fileName;
        }

        $slide = HomeHeroSlide::create([
            'subtitle' => $request->subtitle,
            'title' => $request->title,
            'desc' => $request->desc,
            'image' => $imagePath
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Hero slide created successfully.',
            'slide' => $slide
        ]);
    }

    public function update(Request $request, $id)
    {
        $slide = HomeHeroSlide::findOrFail($id);

        $request->validate([
            'subtitle' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        if (!$request->hasFile('image_file') && !$request->filled('image') && !$slide->image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed: Either an image file upload or an image URL is required.'
            ], 422);
        }

        $imagePath = $request->input('image') ?: $slide->image;

        if ($request->hasFile('image_file')) {
            // Delete old local file if any
            if ($slide->image && !str_starts_with($slide->image, 'http') && !str_starts_with($slide->image, '/photo/')) {
                @unlink(storage_path('app/public/' . $slide->image));
            }
            $file = $request->file('image_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/hero'), $fileName);
            $imagePath = 'hero/' . $fileName;
        }

        $slide->update([
            'subtitle' => $request->subtitle,
            'title' => $request->title,
            'desc' => $request->desc,
            'image' => $imagePath
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Hero slide updated successfully.',
            'slide' => $slide
        ]);
    }

    public function destroy($id)
    {
        $slide = HomeHeroSlide::findOrFail($id);
        
        // Delete old local file if any
        if ($slide->image && !str_starts_with($slide->image, 'http') && !str_starts_with($slide->image, '/photo/')) {
            @unlink(storage_path('app/public/' . $slide->image));
        }
        
        $slide->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Hero slide deleted successfully.'
        ]);
    }
}
