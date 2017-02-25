<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MissionComment extends Model
{
    protected $fillable = [
        'id',
        'mission_id',
        'user_id',
        'text',
        'published'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function mission()
    {
        return $this->belongsTo('App\Mission');
    }

    public function isMine()
    {
        return $this->user_id == auth()->user()->id;
    }
}
