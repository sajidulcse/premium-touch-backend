<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    protected $fillable = ['service_id', 'image_path', 'is_thumbnail', 'alt_text'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
