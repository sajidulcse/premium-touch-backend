<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavbarItem extends Model
{
    protected $table ='navbar_items';
    
    protected $fillable = [
        'title', 'slug', 'url', 'position', 'status'
    ];
}
