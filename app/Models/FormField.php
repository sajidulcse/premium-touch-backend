<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class FormField extends Model
{
    use LogsActivity;

    protected $table = 'form_fields';

    protected $fillable = [
        'field_name',
        'label',
        'type',
        'placeholder',
        'options',
        'is_required',
        'is_enabled',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_enabled' => 'boolean',
        'order' => 'integer',
    ];
}
