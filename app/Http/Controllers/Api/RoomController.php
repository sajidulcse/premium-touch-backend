<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms.
     */
    public function index()
    {
        $rooms = Room::orderBy('name', 'asc')->get();
        return response()->json($rooms);
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
            'icon' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure slug is unique
        $count = Room::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }

        $room = Room::create($validated);

        Cache::forget('estimator_config_v1');

        return response()->json([
            'message' => 'Room created successfully.',
            'data' => $room
        ], 201);
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room)
    {
        return response()->json($room);
    }

    /**
     * Update the specified room.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
            'icon' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure slug is unique excluding self
        $count = Room::where('slug', 'like', $validated['slug'] . '%')->where('id', '!=', $room->id)->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }

        $room->update($validated);

        Cache::forget('estimator_config_v1');

        return response()->json([
            'message' => 'Room updated successfully.',
            'data' => $room
        ]);
    }

    /**
     * Remove the specified room.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        Cache::forget('estimator_config_v1');
        return response()->json(['message' => 'Room deleted successfully.']);
    }
}
