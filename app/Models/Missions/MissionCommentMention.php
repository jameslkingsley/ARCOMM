<?php

namespace App\Models\Missions;

use Illuminate\Database\Eloquent\Model;
use App\Models\Missions\MissionComment;
use App\Models\Portal\User;

class MissionCommentMention extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mission_comment_id',
        'user_id'
    ];

    /**
     * Gets the mentioned user.
     *
     * @return App\Models\Portal\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gets the mission comment.
     *
     * @return App\Models\Missions\MissionComment
     */
    public function comment()
    {
        return $this->belongsTo(MissionComment::class);
    }
}
