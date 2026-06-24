<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeIdentity extends Model
{
    protected $table = 'home_identities';

    protected $fillable = ['subtitle', 'title', 'description', 'image'];
}
