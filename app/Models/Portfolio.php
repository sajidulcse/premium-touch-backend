<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'child_category_id',
        'title',
        'slug',
        'description',
        'faqs',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function childCategory()
    {
        return $this->belongsTo(Category::class, 'child_category_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($portfolio) {
            if (empty($portfolio->slug)) {
                $portfolio->slug = \Illuminate\Support\Str::slug($portfolio->title);
            }
        });
        static::updating(function ($portfolio) {
            if ($portfolio->isDirty('title')) {
                $portfolio->slug = \Illuminate\Support\Str::slug($portfolio->title);
            }
        });
    }

    public function images()
    {
        return $this->hasMany(PortfolioImage::class);
    }

    public function thumbnail()
    {
        return $this->hasOne(PortfolioImage::class)->where('is_thumbnail', true);
    }
}
