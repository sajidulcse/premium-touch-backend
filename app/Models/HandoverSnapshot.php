<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HandoverSnapshot extends Model
{
    protected $table = 'handover_snapshots';

    protected $fillable = [
        'title',
        'client',
        'image_path',
        'date',
        'position'
    ];

    public $timestamps = true;
}
