<?php

namespace App\Models\JoinRequests;

use Illuminate\Database\Eloquent\Model;

class JoinStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'permalink',
        'position'
    ];
}
