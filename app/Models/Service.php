<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'sub_category_id',
        'description',
        'faqs',
        'status'
    ];

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function images()
    {
        return $this->hasMany(ServiceImage::class, 'service_id');
    }

    public function thumbnail()
    {
        return $this->hasOne(ServiceImage::class)->where('is_thumbnail', true);
    }
}
