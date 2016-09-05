<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    protected $fillable = [
        'name',
        'age',
        'location',
        'email',
        'steam',
        'available',
        'apex',
        'groups',
        'experience',
        'bio'
    ];
}
