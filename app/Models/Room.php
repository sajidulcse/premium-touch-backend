<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Room extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'icon',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function addons()
    {
        return $this->hasMany(Addon::class);
    }
}
