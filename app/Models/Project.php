<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'child_category_id',
        'title',
        'slug',
        'description',
        'location',
        'client_name',
        'completion_date',
        'duration',
        'floor_area',
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
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = \Illuminate\Support\Str::slug($project->title);
            }
        });
        static::updating(function ($project) {
            if ($project->isDirty('title')) {
                $project->slug = \Illuminate\Support\Str::slug($project->title);
            }
        });
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function thumbnail()
    {
        return $this->hasOne(ProjectImage::class)->where('is_thumbnail', true);
    }
}
