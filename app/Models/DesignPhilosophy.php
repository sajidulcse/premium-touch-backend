<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignPhilosophy extends Model
{
    protected $table = 'design_philosophies';

    protected $fillable = ['step_number', 'title', 'image', 'description'];

    protected $appends = ['stepNumber'];

    public function getStepNumberAttribute()
    {
        return $this->attributes['step_number'] ?? null;
    }
}
