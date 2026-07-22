<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class EstimatorSetting extends Model
{
    use LogsActivity;

    protected $table = 'estimator_settings';

    protected $fillable = [
        'pdf_title',
        'pdf_company_name',
        'pdf_address',
        'pdf_phone',
        'pdf_footer_text',
        'pdf_terms_conditions',
        'logo',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
