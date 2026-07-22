<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Comment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'blog_id', 
        'user_name', 
        'user_email', 
        'comment', 
        'is_approved', 
        'parent_id', 
        'is_admin_reply',
        'is_edited'
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
