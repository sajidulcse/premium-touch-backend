<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Package extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'base_rate',
        'description',
        'display_order',
        'status',
    ];

    protected $casts = [
        'status'    => 'boolean',
        'base_rate' => 'decimal:2',
    ];

    public function leads()
    {
        return $this->hasMany(EstimatorLead::class);
    }

    public function addonPrices()
    {
        return $this->hasMany(AddonPackagePrice::class);
    }
}
