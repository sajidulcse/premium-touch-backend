<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false; // Managed via created_at only (no updated_at)

    protected $fillable = [
        'user_id',
        'action',
        'loggable_type',
        'loggable_id',
        'description',
        'old_properties',
        'new_properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_properties' => 'array',
        'new_properties' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the performing user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent loggable model (polymorphic).
     */
    public function loggable()
    {
        return $this->morphTo();
    }
}
