<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Addon extends Model
{
    use LogsActivity;

    protected $fillable = [
        'room_id',
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function prices()
    {
        return $this->hasMany(AddonPackagePrice::class);
    }
}
