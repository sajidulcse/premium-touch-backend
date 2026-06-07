<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
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
