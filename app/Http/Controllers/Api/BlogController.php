<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogImage;
use App\Models\BlogReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        // Only show published blogs to public
        return response()->json(
            Blog::where('status', 'published')
                ->with(['images', 'category'])
                ->withCount(['comments', 'likes', 'dislikes'])
                ->latest()
                ->get()
        );
    }

    public function adminIndex()
    {
        // Admin needs to see everything (drafts + published)
        return response()->json(
            Blog::with(['images', 'category'])
                ->withCount(['comments', 'likes', 'dislikes'])
                ->latest()
                ->get()
        );
    }

    public function recentBlogs()
    {
        return response()->json(
            Blog::where('status', 'published')
                ->with(['images', 'category'])
                ->latest()
                ->take(5)
                ->get()
        );
    }

    public function show($slug)
    {
        $blog = Blog::with(['images', 'category', 'likes', 'dislikes', 'comments' => function($query) {
            $query->whereNull('parent_id')->with('replies')->latest();
        }])->where('slug', $slug)->firstOrFail();
        
        return response()->json($blog);
    }

    public function adminShow($id)
    {
        return response()->json(Blog::with(['images', 'category'])->findOrFail($id));
    }

    public function categoryBlogs($slug)
    {
        $blogs = Blog::whereHas('category', function($q) use ($slug) {
            $q->where('slug', $slug);
        })
        ->where('status', 'published')
        ->with(['images', 'category'])
        ->withCount(['comments', 'likes', 'dislikes'])
        ->latest()
        ->get();

        return response()->json($blogs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:published,draft',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $blog = Blog::create([
            'blog_category_id' => $request->blog_category_id,
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author ?? 'Admin',
            'status' => $request->status,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('blogs', 'public');
                BlogImage::create([
                    'blog_id' => $blog->id,
                    'image_path' => $path
                ]);
            }
        }

        return response()->json(['message' => 'Blog created successfully', 'blog' => $blog]);
    }

    public function update(Request $request, $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            $request->validate([
                'title' => 'string|max:255',
                'status' => 'in:published,draft',
                'blog_category_id' => 'nullable|exists:blog_categories,id',
                'content' => 'sometimes|string',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($request->has('title')) {
                $blog->title = $request->title;
            }
            
            if ($request->has('content')) $blog->content = $request->content;
            if ($request->has('status')) $blog->status = $request->status;
            if ($request->has('author')) $blog->author = $request->author;
            if ($request->has('blog_category_id')) {
                $blog->blog_category_id = $request->blog_category_id === 'null' || $request->blog_category_id === '' ? null : $request->blog_category_id;
            }

            $blog->save();

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('blogs', 'public');
                    BlogImage::create([
                        'blog_id' => $blog->id,
                        'image_path' => $path
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Blog updated successfully',
                'blog' => $blog->load(['images', 'category'])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update blog: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        foreach ($blog->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $blog->delete();
        return response()->json(['message' => 'Blog deleted successfully']);
    }

    public function deleteImage($id)
    {
        $image = BlogImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return response()->json(['message' => 'Image deleted successfully']);
    }

    public function incrementView(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        
        // Simple protection using session
        $viewedKey = 'view_counted_' . $blog->id;
        if (!session()->has($viewedKey)) {
            $blog->increment('views');
            session()->put($viewedKey, true);
            return response()->json(['message' => 'View counted', 'views' => $blog->views]);
        }
        
        return response()->json(['message' => 'View already counted', 'views' => $blog->views]);
    }

    public function react(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $ip = $request->ip();
        
        $existing = BlogReaction::where('blog_id', $id)
            ->where('ip_address', $ip)
            ->first();

        if ($existing) {
            if ($existing->type === $request->type) {
                $existing->delete();
                return response()->json(['message' => 'Reaction removed']);
            } else {
                $existing->type = $request->type;
                $existing->save();
                return response()->json(['message' => 'Reaction updated']);
            }
        }

        BlogReaction::create([
            'blog_id' => $id,
            'type' => $request->type,
            'ip_address' => $ip
        ]);

        return response()->json(['message' => 'Reaction added']);
    }
    public function uploadContentImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs/content', 'public');
            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }
        return response()->json(['error' => 'No image provided'], 400);
    }
}
