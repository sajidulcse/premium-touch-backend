<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class TeamMember extends Model
{
    use LogsActivity;

    protected $table = 'team_members';

    protected $fillable = [
        'name',
        'role',
        'desc',
        'quote',
        'image',
        'linkedin',
        'instagram',
        'facebook',
        'email',
        'website',
        'is_executive',
        'position'
    ];

    protected $casts = [
        'is_executive' => 'boolean'
    ];
}
