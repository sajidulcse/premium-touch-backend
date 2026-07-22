<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PackageController extends Controller
{
    /**
     * Display a listing of packages.
     */
    public function index()
    {
        $packages = Package::orderBy('display_order', 'asc')->get();
        return response()->json($packages);
    }

    /**
     * Store a newly created package.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'base_rate'     => 'nullable|numeric|min:0',
            'description'   => 'nullable|string',
            'display_order' => 'nullable|integer',
            'status'        => 'nullable|boolean',
        ]);

        $package = Package::create($validated);

        Cache::forget('estimator_config_v1');

        return response()->json([
            'message' => 'Package created successfully.',
            'data'    => $package
        ], 201);
    }

    /**
     * Display the specified package.
     */
    public function show(Package $package)
    {
        return response()->json($package);
    }

    /**
     * Update the specified package.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'base_rate'     => 'nullable|numeric|min:0',
            'description'   => 'nullable|string',
            'display_order' => 'nullable|integer',
            'status'        => 'nullable|boolean',
        ]);

        $package->update($validated);

        Cache::forget('estimator_config_v1');

        return response()->json([
            'message' => 'Package updated successfully.',
            'data'    => $package
        ]);
    }

    /**
     * Remove the specified package.
     */
    public function destroy(Package $package)
    {
        $package->delete();
        Cache::forget('estimator_config_v1');
        return response()->json(['message' => 'Package deleted successfully.']);
    }
}
