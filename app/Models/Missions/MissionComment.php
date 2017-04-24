<?php

namespace App\Models\Missions;

use Illuminate\Database\Eloquent\Model;
use App\Models\Portal\User;

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

    /**
     * Gets all user mentions in the comment text.
     *
     * @return Collection App\Models\Portal\User
     */
    public function mentions()
    {
        $mentions = collect([]);

        $chars = explode(' ', $this->text);

        foreach ($chars as $char) {
            if (starts_with($char, '@')) {
                $name = substr($char, 1);
                $user = User::where('username', $name)->first();
                
                if ($user) {
                    $mentions->push($user);
                }
            }
        }

        return $mentions;
    }
}
