<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CareerOpening;
use Illuminate\Http\Request;

class CareerOpeningController extends Controller
{
    public function index(Request $request)
    {
        $query = CareerOpening::orderBy('position', 'asc')->orderBy('id', 'asc');
        
        if ($request->has('active_only') && ($request->input('active_only') === 'true' || $request->input('active_only') == '1')) {
            $query->where('status', 1);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'exp' => 'required|string|max:255',
                'desc' => 'required|string',
                'status' => 'nullable|in:true,false,1,0',
                'position' => 'nullable|integer'
            ]);

            $data = $request->only(['title', 'type', 'location', 'exp', 'desc']);
            
            $status = $request->input('status');
            $data['status'] = ($status === null || $status === 'true' || $status === '1');

            if (!$request->filled('position')) {
                $maxPosition = CareerOpening::max('position') ?? 0;
                $data['position'] = $maxPosition + 1;
            } else {
                $data['position'] = (int)$request->input('position');
            }

            $opening = CareerOpening::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Career opening added successfully.',
                'opening' => $opening
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save opening: ' . $e->getMessage(),
                'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null
            ], 500);
        }
    }

    public function show($id)
    {
        return response()->json(CareerOpening::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        try {
            $opening = CareerOpening::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'exp' => 'required|string|max:255',
                'desc' => 'required|string',
                'status' => 'nullable|in:true,false,1,0',
                'position' => 'nullable|integer'
            ]);

            $data = $request->only(['title', 'type', 'location', 'exp', 'desc']);

            if ($request->has('status')) {
                $status = $request->input('status');
                $data['status'] = ($status === 'true' || $status === '1');
            }

            if ($request->has('position')) {
                $data['position'] = (int)$request->input('position');
            }

            $opening->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Career opening updated successfully.',
                'opening' => $opening
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update opening: ' . $e->getMessage(),
                'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $opening = CareerOpening::findOrFail($id);
            $opening->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Career opening deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete career opening: ' . $e->getMessage()
            ], 500);
        }
    }
}
