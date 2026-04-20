<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'site_name',
        'tagline',
        'logo',
        'short_description',
        'phone',
        'email',
        'address',
        'map_embed_url',
        'map_url',
        'facebook_page_url',
        'project_header_bg'
    ];

    public $timestamps = true;
}
