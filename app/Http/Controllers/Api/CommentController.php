<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return response()->json(Comment::with(['blog', 'replies'])->whereNull('parent_id')->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email',
            'comment' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
            'is_admin_reply' => 'nullable|boolean'
        ]);

        $comment = Comment::create([
            'blog_id' => $request->blog_id,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
            'is_admin_reply' => $request->is_admin_reply ?? false,
            // All comments are auto-approved
            'is_approved' => true,
        ]);

        return response()->json(['message' => 'Comment submitted', 'comment' => $comment]);
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->is_approved = true;
        $comment->save();
        return response()->json(['message' => 'Comment approved successfully']);
    }

    public function disapprove($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->is_approved = false;
        $comment->save();
        return response()->json(['message' => 'Comment disapproved successfully']);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
