<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ProcessStep extends Model
{
    use LogsActivity;

    protected $table = 'process_steps';

    protected $fillable = ['step_number', 'title', 'image', 'description'];

    protected $appends = ['stepNumber'];

    public function getStepNumberAttribute()
    {
        return $this->attributes['step_number'] ?? null;
    }
}
