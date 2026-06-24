<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProcessStep;
use Illuminate\Http\Request;

class ProcessStepController extends Controller
{
    public function index()
    {
        return response()->json(ProcessStep::all());
    }

    public function update(Request $request, $id)
    {
        $step = ProcessStep::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'description' => 'required|string'
        ]);

        if (!$request->hasFile('image_file') && !$request->filled('image') && !$step->image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed: Either an image file upload or an image path/URL is required.'
            ], 422);
        }

        $imagePath = $request->input('image') ?: $step->image;

        if ($request->hasFile('image_file')) {
            // Delete old local file if any
            if ($step->image && !str_starts_with($step->image, 'http') && !str_starts_with($step->image, '/photo/')) {
                @unlink(storage_path('app/public/' . $step->image));
            }
            $file = $request->file('image_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/process'), $fileName);
            $imagePath = 'process/' . $fileName;
        }

        $step->update([
            'title' => $request->title,
            'image' => $imagePath,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Process step updated successfully.',
            'step' => $step
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
            $file->move(storage_path('app/public/process'), $fileName);
            $imagePath = 'process/' . $fileName;
        }

        $step = ProcessStep::create([
            'step_number' => $request->step_number,
            'title' => $request->title,
            'image' => $imagePath,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Process step added successfully.',
            'step' => $step
        ]);
    }

    public function destroy($id)
    {
        $step = ProcessStep::findOrFail($id);

        if ($step->image && !str_starts_with($step->image, 'http') && !str_starts_with($step->image, '/photo/')) {
            @unlink(storage_path('app/public/' . $step->image));
        }

        $step->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Process step deleted successfully.'
        ]);
    }
}
