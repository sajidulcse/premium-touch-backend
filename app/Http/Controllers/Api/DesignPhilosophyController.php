<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DesignPhilosophy;
use Illuminate\Http\Request;

class DesignPhilosophyController extends Controller
{
    public function index()
    {
        return response()->json(DesignPhilosophy::all());
    }

    public function update(Request $request, $id)
    {
        $philosophy = DesignPhilosophy::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'description' => 'required|string'
        ]);

        if (!$request->hasFile('image_file') && !$request->filled('image') && !$philosophy->image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed: Either an image file upload or an image path/URL is required.'
            ], 422);
        }

        $imagePath = $request->input('image') ?: $philosophy->image;

        if ($request->hasFile('image_file')) {
            // Delete old local file if any
            if ($philosophy->image && !str_starts_with($philosophy->image, 'http') && !str_starts_with($philosophy->image, '/photo/')) {
                @unlink(storage_path('app/public/' . $philosophy->image));
            }
            $file = $request->file('image_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/philosophy'), $fileName);
            $imagePath = 'philosophy/' . $fileName;
        }

        $philosophy->update([
            'title' => $request->title,
            'image' => $imagePath,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Design philosophy updated successfully.',
            'philosophy' => $philosophy
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'step_number' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'description' => 'required|string'
        ]);

        if (!$request->hasFile('image_file') && !$request->filled('image')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed: Either an image file upload or an image path/URL is required.'
            ], 422);
        }

        $imagePath = $request->input('image') ?: '';

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/philosophy'), $fileName);
            $imagePath = 'philosophy/' . $fileName;
        }

        $philosophy = DesignPhilosophy::create([
            'step_number' => $request->step_number,
            'title' => $request->title,
            'image' => $imagePath,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Design philosophy added successfully.',
            'philosophy' => $philosophy
        ]);
    }

    public function destroy($id)
    {
        $philosophy = DesignPhilosophy::findOrFail($id);

        if ($philosophy->image && !str_starts_with($philosophy->image, 'http') && !str_starts_with($philosophy->image, '/photo/')) {
            @unlink(storage_path('app/public/' . $philosophy->image));
        }

        $philosophy->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Design philosophy deleted successfully.'
        ]);
    }
}
