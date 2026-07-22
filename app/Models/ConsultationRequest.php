<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ConsultationRequest extends Model
{
    use LogsActivity;

    protected $table = 'consultation_requests';

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'project_type',
        'property_size',
        'budget_range',
        'location',
        'notes',
        'form_data',
        'internal_notes',
        'status',
        'source',
    ];

    protected $casts = [
        'form_data' => 'array',
    ];
}
