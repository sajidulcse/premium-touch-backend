<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'icon',      // optional: icon or image
        'position',  // for sorting
        'status'     // 1 = active, 0 = inactive
    ];
}
