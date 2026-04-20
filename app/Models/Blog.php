<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = ['blog_category_id', 'title', 'slug', 'content', 'author', 'status', 'views'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = preg_replace('/\s+/u', '-', trim($blog->title));
            }
        });
        static::updating(function ($blog) {
            if ($blog->isDirty('title')) {
                $blog->slug = preg_replace('/\s+/u', '-', trim($blog->title));
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function images()
    {
        return $this->hasMany(BlogImage::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(BlogReaction::class);
    }

    public function likes()
    {
        return $this->hasMany(BlogReaction::class)->where('type', 'like');
    }

    public function dislikes()
    {
        return $this->hasMany(BlogReaction::class)->where('type', 'dislike');
    }
}
