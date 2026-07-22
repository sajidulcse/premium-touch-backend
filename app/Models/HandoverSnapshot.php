<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class HandoverSnapshot extends Model
{
    use LogsActivity;

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
