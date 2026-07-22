<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by loggable model type (morph type)
        if ($request->filled('loggable_type')) {
            $query->where('loggable_type', $request->loggable_type);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $perPage = $request->integer('per_page', 15);
        $logs = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json($logs);
    }
}
