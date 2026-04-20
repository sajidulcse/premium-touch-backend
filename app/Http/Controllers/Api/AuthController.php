<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Handle admin login.
     * Note: In a production app, use Sanctum/Passport for tokens.
     * For this request, we'll return the user object as "login" proof.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => $user
        ]);
    }

    /**
     * Get admin profile.
     */
    public function profile()
    {
        $admin = User::first(); // Assuming single admin for simplicity
        return response()->json($admin);
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        $admin = User::first();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|min:6',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($admin->profile_picture) {
                Storage::delete('public/' . $admin->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $admin->profile_picture = $path;
        }

        $admin->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $admin
        ]);
    }
}
