<?php

namespace App\Models\Missions;

use Illuminate\Database\Eloquent\Model;

class MissionComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'mission_id',
        'user_id',
        'text',
        'published'
    ];

    /**
     * Gets the author of the comment.
     *
     * @return App\Models\Portal\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Portal\User');
    }

    /**
     * Gets the mission the comment belongs to.
     *
     * @return App\Models\Missions\Mission
     */
    public function mission()
    {
        return $this->belongsTo('App\Models\Missions\Mission');
    }

    /**
     * Checks whether the comment belongs to the authenticated user.
     *
     * @return boolean
     */
    public function isMine()
    {
        return $this->user_id == auth()->user()->id;
    }
}
