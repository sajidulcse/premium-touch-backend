<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Video::orderBy('position', 'asc')->orderBy('id', 'asc')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'url' => 'required|url|max:255',
                'description' => 'nullable|string',
                'position' => 'nullable|integer'
            ]);

            $data = $request->only(['title', 'url', 'description']);

            if (!$request->filled('position')) {
                $maxPosition = Video::max('position') ?? 0;
                $data['position'] = $maxPosition + 1;
            } else {
                $data['position'] = (int)$request->input('position');
            }

            $video = Video::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Video link added successfully.',
                'video' => $video
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save video: ' . $e->getMessage(),
                'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(Video::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $video = Video::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'url' => 'required|url|max:255',
                'description' => 'nullable|string',
                'position' => 'nullable|integer'
            ]);

            $data = $request->only(['title', 'url', 'description']);

            if ($request->has('position')) {
                $data['position'] = (int)$request->input('position');
            }

            $video->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Video link updated successfully.',
                'video' => $video
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update video: ' . $e->getMessage(),
                'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $video = Video::findOrFail($id);
            $video->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Video link deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete video: ' . $e->getMessage()
            ], 500);
        }
    }
}
