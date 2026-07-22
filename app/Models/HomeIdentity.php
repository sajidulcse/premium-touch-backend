<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class HomeIdentity extends Model
{
    use LogsActivity;

    protected $table = 'home_identities';

    protected $fillable = ['subtitle', 'title', 'description', 'image'];
}
