<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeHeroSlide extends Model
{
    protected $table = 'home_hero_slides';

    protected $fillable = ['subtitle', 'title', 'desc', 'image'];
}
