<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonPackagePrice extends Model
{
    protected $table = 'addon_package_prices';

    protected $fillable = [
        'addon_id',
        'package_id',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
