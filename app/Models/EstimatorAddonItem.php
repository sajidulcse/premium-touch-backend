<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimatorAddonItem extends Model
{
    protected $table = 'estimator_addon_items';

    protected $fillable = [
        'estimator_lead_id',
        'addon_id',
        'quantity',
    ];

    public function lead()
    {
        return $this->belongsTo(EstimatorLead::class, 'estimator_lead_id');
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}
