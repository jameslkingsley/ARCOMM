<?php

namespace App\Models\Missions;

use Illuminate\Database\Eloquent\Model;
use Kingsley\Mentions\HasMentionsTrait;
use App\Models\Missions\Mission;
use App\Models\Portal\User;

class MissionNote extends Model
{
    use HasMentionsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'mission_id',
        'text'
    ];

    /**
     * Gets the user model the note belongs to.
     *
     * @return App\Models\Portal\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gets the mission model the note belongs to.
     *
     * @return App\Models\Missions\Mission
     */
    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    /**
     * Checks if the note is mine.
     *
     * @return boolean
     */
    public function isMine()
    {
        return $this->user_id == auth()->user()->id;
    }
}
