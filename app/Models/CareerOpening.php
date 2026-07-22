<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class CareerOpening extends Model
{
    use LogsActivity;

    protected $table = 'career_openings';

    protected $fillable = [
        'title',
        'type',
        'location',
        'exp',
        'desc',
        'status',
        'position'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];
}
