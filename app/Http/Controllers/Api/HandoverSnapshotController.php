<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HandoverSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HandoverSnapshotController extends Controller
{
    public function index()
    {
        return response()->json(
            HandoverSnapshot::orderByRaw('date IS NULL, date DESC')->orderBy('position', 'asc')->orderBy('id', 'asc')->get()
        );
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'client' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'date' => 'nullable|date',
                'position' => 'nullable|integer'
            ]);

            if (!$request->hasFile('image') && !$request->hasFile('images')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed: At least one image is required.'
                ], 422);
            }

            $snapshots = [];
            $maxPosition = HandoverSnapshot::max('position') ?? 0;
            $position = $maxPosition + 1;

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(storage_path('app/public/handovers'), $fileName);
                    $imagePath = 'handovers/' . $fileName;

                    $snapshots[] = HandoverSnapshot::create([
                        'title' => $request->title,
                        'client' => $request->client,
                        'image_path' => $imagePath,
                        'date' => $request->date,
                        'position' => $position++
                    ]);
                }
            } elseif ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/handovers'), $fileName);
                $imagePath = 'handovers/' . $fileName;

                $snapshots[] = HandoverSnapshot::create([
                    'title' => $request->title,
                    'client' => $request->client,
                    'image_path' => $imagePath,
                    'date' => $request->date,
                    'position' => $position
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => count($snapshots) > 1 
                    ? 'Handover Snapshots added successfully.' 
                    : 'Handover Snapshot added successfully.',
                'snapshots' => $snapshots
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save snapshot: ' . $e->getMessage(),
                'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $snapshot = HandoverSnapshot::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'client' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'date' => 'nullable|date',
                'existing_image_deleted' => 'nullable|string'
            ]);

            $data = $request->only(['title', 'client', 'date']);
            
            $existingDeleted = $request->input('existing_image_deleted') === 'true';
            $newFiles = $request->file('images') ?: [];

            // If a single 'image' file is sent, treat it as the first new file
            if ($request->hasFile('image')) {
                $newFiles = array_merge([$request->file('image')], $newFiles);
            }

            if ($existingDeleted || count($newFiles) > 0) {
                if (count($newFiles) > 0) {
                    // Replace current image with the first uploaded file
                    $firstFile = array_shift($newFiles);
                    
                    if ($snapshot->image_path) {
                        Storage::disk('public')->delete($snapshot->image_path);
                    }
                    
                    $fileName = time() . '_' . uniqid() . '.' . $firstFile->getClientOriginalExtension();
                    $firstFile->move(storage_path('app/public/handovers'), $fileName);
                    $data['image_path'] = 'handovers/' . $fileName;
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cannot delete the existing image without providing a new replacement image.'
                    ], 422);
                }
            }

            $snapshot->update($data);

            // Create new snapshot entries for any additional uploaded files
            $newSnapshots = [];
            if (count($newFiles) > 0) {
                $maxPosition = HandoverSnapshot::max('position') ?? 0;
                $position = $maxPosition + 1;

                foreach ($newFiles as $file) {
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(storage_path('app/public/handovers'), $fileName);
                    $imagePath = 'handovers/' . $fileName;

                    $newSnapshots[] = HandoverSnapshot::create([
                        'title' => $snapshot->title,
                        'client' => $snapshot->client,
                        'image_path' => $imagePath,
                        'date' => $snapshot->date,
                        'position' => $position++
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Handover Snapshot updated successfully.',
                'snapshot' => $snapshot,
                'new_snapshots' => $newSnapshots
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update snapshot: ' . $e->getMessage(),
                'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null
            ], 500);
        }
    }

    public function destroy($id)
    {
        $snapshot = HandoverSnapshot::findOrFail($id);
        if ($snapshot->image_path) {
            Storage::disk('public')->delete($snapshot->image_path);
        }
        $snapshot->delete();

        return response()->json(['message' => 'Handover Snapshot deleted successfully']);
    }
}
