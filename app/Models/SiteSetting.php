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
        'header_bg',
        'stat_1_num',
        'stat_1_label',
        'stat_2_num',
        'stat_2_label',
        'stat_3_num',
        'stat_3_label',
        'stat_4_num',
        'stat_4_label',
        'cta_bg'
    ];

    public $timestamps = true;
}
