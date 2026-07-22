<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status (active/inactive)
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('is_active', filter_var($request->status, FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users = $query->with('roles')
            ->orderBy('id', 'desc')
            ->paginate($request->input('per_page', 10));

        // Format user list for frontend consumption
        $users->getCollection()->transform(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $user->profile_picture,
                'is_active' => $user->is_active,
                'last_login_at' => $user->last_login_at ? $user->last_login_at->toIso8601String() : null,
                'role' => $user->getRoleNames()->first() ?? 'No Role',
            ];
        });

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->input('is_active', true),
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'User created successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'role' => $user->getRoleNames()->first(),
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_picture' => $user->profile_picture,
            'is_active' => $user->is_active,
            'last_login_at' => $user->last_login_at ? $user->last_login_at->toIso8601String() : null,
            'role' => $user->getRoleNames()->first() ?? 'No Role',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        // Prevent Super Admin from deactivating themselves
        if ($user->id === $request->user()->id && $request->has('is_active') && !$request->is_active) {
            return response()->json(['message' => 'You cannot deactivate your own account.'], 400);
        }

        // Prevent Super Admin from changing their own role
        if ($user->id === $request->user()->id && $request->role !== $user->getRoleNames()->first()) {
            return response()->json(['message' => 'You cannot change your own role.'], 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->has('is_active')) {
            $user->is_active = $request->is_active;
        }
        
        $user->save();

        // Sync role
        $user->syncRoles([$request->role]);

        // Revoke active tokens if deactivated
        if (!$user->is_active) {
            $user->tokens()->delete();
        }

        return response()->json([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'role' => $user->getRoleNames()->first(),
            ]
        ]);
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot change your own status.'], 400);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        if (!$user->is_active) {
            $user->tokens()->delete(); // Immediately revoke sessions
        }

        try {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'model_type' => User::class,
                'model_id' => $user->id,
                'description' => ($user->is_active ? "Activated" : "Deactivated") . " user account '{$user->name}'",
                'old_properties' => ['is_active' => !$user->is_active],
                'new_properties' => ['is_active' => $user->is_active],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {}

        return response()->json([
            'message' => $user->is_active ? 'User activated successfully.' : 'User deactivated successfully.',
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Reset a user's password.
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        // Revoke all tokens for security
        $user->tokens()->delete();

        try {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'model_type' => User::class,
                'model_id' => $user->id,
                'description' => "Reset password for user '{$user->name}'",
                'old_properties' => null,
                'new_properties' => ['password' => '********'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {}

        return response()->json([
            'message' => "Password for user {$user->name} reset successfully."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent self deletion
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 400);
        }

        // Prevent deletion of the last Super Admin
        if ($user->hasRole('Super Admin')) {
            $superAdminsCount = User::role('Super Admin')->where('is_active', true)->count();
            if ($superAdminsCount <= 1) {
                return response()->json(['message' => 'You cannot delete the last active Super Admin.'], 400);
            }
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }
}
