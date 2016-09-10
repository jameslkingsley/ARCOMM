<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JoinStatus extends Model
{
    protected $fillable = [
        'text',
        'permalink',
        'position'
    ];
}
