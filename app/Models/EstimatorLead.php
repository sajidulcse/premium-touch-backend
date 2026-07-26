<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimatorLead extends Model
{
    protected $table = 'estimator_leads';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'location',
        'project_address',
        'home_size',
        'flat_status',
        'package_id',
        'total_estimate',
    ];

    protected $casts = [
        'home_size'      => 'decimal:2',
        'total_estimate' => 'decimal:2',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function roomItems()
    {
        return $this->hasMany(EstimatorRoomItem::class, 'estimator_lead_id');
    }

    public function addonItems()
    {
        return $this->hasMany(EstimatorAddonItem::class, 'estimator_lead_id');
    }
}
