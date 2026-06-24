<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeIdentity;
use Illuminate\Http\Request;

class HomeIdentityController extends Controller
{
    public function index()
    {
        $identity = HomeIdentity::first();

        // Fallback safety if DB is empty
        if (!$identity) {
            return response()->json([
                'subtitle' => 'OUR IDENTITY',
                'title' => 'Crafting Spaces, Defining Lifestyles',
                'description' => 'We believe that fine architecture and interior spaces are the physical expressions of personality. Our mission is to blend signature craftsmanship, premium marbles, and elegant warm wood veneers into functional, turnkey layout designs.',
                'image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1000&q=80'
            ]);
        }

        return response()->json($identity);
    }

    public function update(Request $request)
    {
        $identity = HomeIdentity::first() ?? new HomeIdentity();

        $request->validate([
            'subtitle' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        if (!$request->hasFile('image_file') && !$request->filled('image') && !$identity->image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed: Either an image file upload or an image URL is required.'
            ], 422);
        }

        $imagePath = $request->input('image') ?: $identity->image;

        if ($request->hasFile('image_file')) {
            // Delete old local file if any
            if ($identity->image && !str_starts_with($identity->image, 'http') && !str_starts_with($identity->image, '/photo/')) {
                @unlink(storage_path('app/public/' . $identity->image));
            }
            $file = $request->file('image_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/identity'), $fileName);
            $imagePath = 'identity/' . $fileName;
        }

        $identity->fill([
            'subtitle' => $request->subtitle,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath
        ]);
        $identity->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Our Identity updated successfully.',
            'identity' => $identity
        ]);
    }
}
