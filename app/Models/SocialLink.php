<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $table = 'social_links';

    protected $fillable = [
        'name',
        'icon', // e.g., font-awesome class or image
        'url',
        'position', // for sorting
        'status'
    ];
}
