<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerOpening extends Model
{
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
