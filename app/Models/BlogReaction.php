<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogReaction extends Model
{
    protected $fillable = ['blog_id', 'type', 'ip_address'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
