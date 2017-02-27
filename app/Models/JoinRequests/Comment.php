<?php

namespace App\Models\JoinRequests;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'join_request_id',
        'text'
    ];
}
