<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimatorRoomItem extends Model
{
    protected $table = 'estimator_room_items';

    protected $fillable = [
        'estimator_lead_id',
        'room_id',
        'quantity',
    ];

    public function lead()
    {
        return $this->belongsTo(EstimatorLead::class, 'estimator_lead_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
