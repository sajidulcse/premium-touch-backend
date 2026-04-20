<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSection extends Model
{
    use HasFactory;

    protected $table = 'footer_sections';

    protected $fillable = [
        'section_title',
        'section_type',
        'content',
        'display_order',
        'status',
    ];

    // If you store JSON in content
    protected $casts = [
        'content' => 'array',
        'status' => 'boolean',
    ];
}
