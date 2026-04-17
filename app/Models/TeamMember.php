<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'role',
        'avatar_path',
        'bio',
        'custom_link',
        'sort_order',
    ];
}
