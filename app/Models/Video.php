<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Video extends Model
{
    use LogsActivity;

    protected $table = 'videos';

    protected $fillable = [
        'title',
        'url',
        'description',
        'position'
    ];

    protected $casts = [
        'position' => 'integer'
    ];
}
