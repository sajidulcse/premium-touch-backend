<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientReview;
use Illuminate\Http\Request;

class ClientReviewController extends Controller
{
    public function index()
    {
        return response()->json(ClientReview::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'quote' => 'required|string',
            'author' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240'
        ]);

        $imagePath = $request->input('image') ?: '';

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/reviews'), $fileName);
            $imagePath = 'reviews/' . $fileName;
        }

        $review = ClientReview::create([
            'quote' => $request->quote,
            'author' => $request->author,
            'location' => $request->location,
            'image' => $imagePath
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Client review added successfully.',
            'review' => $review
        ]);
    }

    public function update(Request $request, $id)
    {
        $review = ClientReview::findOrFail($id);

        $request->validate([
            'quote' => 'required|string',
            'author' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240'
        ]);

        $imagePath = $request->input('image') ?: $review->image;

        if ($request->hasFile('image_file')) {
            // Delete old local file if any
            if ($review->image && !str_starts_with($review->image, 'http') && !str_starts_with($review->image, '/photo/')) {
                @unlink(storage_path('app/public/' . $review->image));
            }
            $file = $request->file('image_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/reviews'), $fileName);
            $imagePath = 'reviews/' . $fileName;
        }

        $review->update([
            'quote' => $request->quote,
            'author' => $request->author,
            'location' => $request->location,
            'image' => $imagePath
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Client review updated successfully.',
            'review' => $review
        ]);
    }

    public function destroy($id)
    {
        $review = ClientReview::findOrFail($id);

        if ($review->image && !str_starts_with($review->image, 'http') && !str_starts_with($review->image, '/photo/')) {
            @unlink(storage_path('app/public/' . $review->image));
        }

        $review->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Client review deleted successfully.'
        ]);
    }
}
