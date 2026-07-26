<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\ActivityLog;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        // Log Activity
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'model_type' => Role::class,
                'model_id' => $role->id,
                'description' => "Created role '{$role->name}'",
                'old_properties' => null,
                'new_properties' => [
                    'name' => $role->name,
                    'permissions' => $request->permissions ?? []
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            logger()->error("Role activity log creation failed: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role->load('permissions')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => "required|string|unique:roles,name,{$role->id}|max:255",
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        // Prevent renaming of protected roles
        $protectedRoles = ['Super Admin'];
        if (in_array($role->name, $protectedRoles) && $role->name !== $request->name) {
            return response()->json(['message' => 'You cannot rename a protected role.'], 400);
        }

        $oldProperties = [
            'name' => $role->name,
            'permissions' => $role->permissions->pluck('name')->toArray()
        ];

        $role->name = $request->name;
        $role->save();

        if ($request->has('permissions')) {
            if (in_array($role->name, $protectedRoles)) {
                return response()->json(['message' => 'The permissions of the Super Admin role are protected and cannot be modified.'], 400);
            }
            $role->syncPermissions($request->permissions);
        }

        // Forget cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Log Activity
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'model_type' => Role::class,
                'model_id' => $role->id,
                'description' => "Updated role '{$role->name}'",
                'old_properties' => $oldProperties,
                'new_properties' => [
                    'name' => $role->name,
                    'permissions' => $request->permissions ?? []
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            logger()->error("Role activity log update failed: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role->load('permissions')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $protectedRoles = ['Super Admin'];
        if (in_array($role->name, $protectedRoles)) {
            return response()->json(['message' => 'Protected roles cannot be deleted.'], 400);
        }

        // Check if role is in use
        if ($role->users()->exists()) {
            return response()->json(['message' => 'Cannot delete role. It is currently assigned to active users.'], 400);
        }

        $roleName = $role->name;
        $roleId = $role->id;
        $oldProperties = [
            'name' => $role->name,
            'permissions' => $role->permissions->pluck('name')->toArray()
        ];

        $role->delete();

        // Forget cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Log Activity
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'model_type' => Role::class,
                'model_id' => $roleId,
                'description' => "Deleted role '{$roleName}'",
                'old_properties' => $oldProperties,
                'new_properties' => null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            logger()->error("Role activity log deletion failed: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'Role deleted successfully.'
        ]);
    }
}
