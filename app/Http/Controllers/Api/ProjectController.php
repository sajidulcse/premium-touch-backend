<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::where('status', 'published')
            ->with(['images', 'thumbnail', 'category', 'subCategory', 'childCategory']);

        if ($request->has('category') && $request->category !== 'all') {
            $slug = $request->category;
            $query->where(function($q) use ($slug) {
                $q->whereHas('category', function($q2) use ($slug) {
                    $q2->where('slug', $slug);
                })->orWhereHas('subCategory', function($q2) use ($slug) {
                    $q2->where('slug', $slug);
                })->orWhereHas('childCategory', function($q2) use ($slug) {
                    $q2->where('slug', $slug);
                });
            });
        }

        if ($request->has('area') && $request->area !== 'all') {
            $range = $request->area;
            $query->where(function ($q) use ($range) {
                // Ensure we only process rows that have a numeric-looking floor_area
                $q->where('floor_area', 'regexp', '[0-9]');
                
                $isPlus = str_contains($range, '+');
                // Handle the case where '+' might be a space in URL
                if (!$isPlus && str_contains($range, ' ') && is_numeric(trim($range))) {
                    $isPlus = true;
                }

                if ($isPlus) {
                    $min = (int)str_replace(['+', ' '], '', $range);
                    $q->whereRaw("CAST(REGEXP_REPLACE(floor_area, '[^0-9]', '') AS UNSIGNED) >= ?", [$min]);
                } else {
                    $parts = explode('-', $range);
                    if (count($parts) === 2) {
                        $min = (int)$parts[0];
                        $max = (int)$parts[1];
                        $q->whereRaw("CAST(REGEXP_REPLACE(floor_area, '[^0-9]', '') AS UNSIGNED) BETWEEN ? AND ?", [$min, $max]);
                    }
                }
            });
        }

        return response()->json($query->latest()->get());
    }

    public function adminIndex()
    {
        return response()->json(
            Project::with(['images', 'thumbnail', 'category', 'subCategory', 'childCategory'])
                ->latest()
                ->get()
        );
    }

    public function show($slug)
    {
        $project = Project::with(['images', 'thumbnail', 'category', 'subCategory', 'childCategory'])
            ->where('slug', $slug)
            ->firstOrFail();
        
        return response()->json($project);
    }

    public function adminShow($id)
    {
        return response()->json(Project::with(['images', 'thumbnail', 'category', 'subCategory', 'childCategory'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        try {
            // Convert empty strings or 'null' strings to real nulls before validation
            $toNull = ['category_id', 'sub_category_id', 'child_category_id', 'completion_date', 'location', 'client_name', 'duration', 'floor_area'];
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
                'location' => 'nullable|string',
                'client_name' => 'nullable|string',
                'completion_date' => 'nullable|date',
                'duration' => 'nullable|string',
                'floor_area' => 'nullable|string',
                'status' => 'required|in:published,draft',
                'category_id' => 'nullable|exists:categories,id',
                'sub_category_id' => 'nullable|exists:categories,id',
                'child_category_id' => 'nullable|exists:categories,id',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
            ]);

            $project = Project::create($request->only([
                'title', 'description', 'location', 'client_name', 'completion_date', 'duration', 'floor_area', 'status',
                'category_id', 'sub_category_id', 'child_category_id'
            ]));

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $index => $image) {
                    $path = $image->store('projects', 'public');
                    ProjectImage::create([
                        'project_id' => $project->id,
                        'image_path' => $path,
                        'is_thumbnail' => ($index == 0)
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Project created successfully',
                'project' => $project->load(['images', 'thumbnail', 'category'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save project: ' . $e->getMessage(),
                'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);

            // Convert empty strings or 'null' strings to real nulls before validation
            $toNull = ['category_id', 'sub_category_id', 'child_category_id', 'completion_date', 'location', 'client_name', 'duration', 'floor_area'];
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
                'location' => 'nullable|string',
                'client_name' => 'nullable|string',
                'completion_date' => 'nullable|date',
                'duration' => 'nullable|string',
                'floor_area' => 'nullable|string',
                'status' => 'in:published,draft',
                'category_id' => 'nullable|exists:categories,id',
                'sub_category_id' => 'nullable|exists:categories,id',
                'child_category_id' => 'nullable|exists:categories,id',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'thumbnail_id' => 'nullable|exists:project_images,id'
            ]);

            $project->update($request->only([
                'title', 'description', 'location', 'client_name', 'completion_date', 'duration', 'floor_area', 'status',
                'category_id', 'sub_category_id', 'child_category_id'
            ]));

            if ($request->has('thumbnail_id')) {
                ProjectImage::where('project_id', $project->id)->update(['is_thumbnail' => false]);
                ProjectImage::where('id', $request->thumbnail_id)->update(['is_thumbnail' => true]);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('projects', 'public');
                    ProjectImage::create([
                        'project_id' => $project->id,
                        'image_path' => $path,
                        'is_thumbnail' => false
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Project updated successfully',
                'project' => $project->load(['images', 'thumbnail', 'category', 'subCategory', 'childCategory'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update project: ' . $e->getMessage(),
                'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null
            ], 500);
        }
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }

    public function deleteImage($id)
    {
        $image = ProjectImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        
        // If the deleted image was the thumbnail, set another one as thumbnail if exists
        if ($image->is_thumbnail) {
            $nextImage = ProjectImage::where('project_id', $image->project_id)->first();
            if ($nextImage) {
                $nextImage->update(['is_thumbnail' => true]);
            }
        }
        
        return response()->json(['message' => 'Image deleted successfully']);
    }
}
