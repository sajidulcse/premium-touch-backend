<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AddonController extends Controller
{
    /**
     * Display a listing of add-ons with their associated room and package prices.
     */
    public function index()
    {
        $addons = Addon::with(['room', 'prices'])->orderBy('name', 'asc')->get();
        return response()->json($addons);
    }

    /**
     * Store a newly created add-on.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'name' => 'required|string|max:255',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|boolean',
            'prices' => 'required|array',
            'prices.*.package_id' => 'required|integer|exists:packages,id',
            'prices.*.price' => 'required|numeric|min:0',
        ]);

        $addon = Addon::create($validated);

        if (isset($validated['prices'])) {
            foreach ($validated['prices'] as $priceData) {
                $addon->prices()->updateOrCreate(
                    ['package_id' => $priceData['package_id']],
                    ['price' => $priceData['price']]
                );
            }
        }

        $addon->load(['room', 'prices']);

        Cache::forget('estimator_config_v1');

        return response()->json([
            'message' => 'Add-on created successfully.',
            'data' => $addon
        ], 201);
    }

    /**
     * Display the specified add-on.
     */
    public function show(Addon $addon)
    {
        $addon->load(['room', 'prices']);
        return response()->json($addon);
    }

    /**
     * Update the specified add-on.
     */
    public function update(Request $request, Addon $addon)
    {
        $validated = $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'name' => 'required|string|max:255',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|boolean',
            'prices' => 'required|array',
            'prices.*.package_id' => 'required|integer|exists:packages,id',
            'prices.*.price' => 'required|numeric|min:0',
        ]);

        $addon->update($validated);

        if (isset($validated['prices'])) {
            $packageIds = [];
            foreach ($validated['prices'] as $priceData) {
                $addon->prices()->updateOrCreate(
                    ['package_id' => $priceData['package_id']],
                    ['price' => $priceData['price']]
                );
                $packageIds[] = $priceData['package_id'];
            }
            $addon->prices()->whereNotIn('package_id', $packageIds)->delete();
        }

        $addon->load(['room', 'prices']);

        Cache::forget('estimator_config_v1');

        return response()->json([
            'message' => 'Add-on updated successfully.',
            'data' => $addon
        ]);
    }

    /**
     * Remove the specified add-on.
     */
    public function destroy(Addon $addon)
    {
        $addon->delete();
        Cache::forget('estimator_config_v1');
        return response()->json(['message' => 'Add-on deleted successfully.']);
    }
}
