<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class HomeHeroSlide extends Model
{
    use LogsActivity;

    protected $table = 'home_hero_slides';

    protected $fillable = ['subtitle', 'title', 'desc', 'image'];
}
