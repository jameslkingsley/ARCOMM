<?php

namespace App\Models\Missions;

use Illuminate\Database\Eloquent\Model;
use App\Models\Missions\MissionCommentMention;
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
     * Gets all user mentions in the comment.
     *
     * @return Collection App\Models\Missions\MissionCommentMention
     */
    public function mentions()
    {
        return $this->hasMany(MissionCommentMention::class);
    }

    /**
     * Gets all user mentions in the comment as a users collection.
     *
     * @return Collection App\Models\Portal\User
     */
    public function mentionsAsUser()
    {
        return $this->mentions->map(function($mention) {
            return $mention->user;
        });
    }

    /**
     * Gets the computed text of a comment.
     *
     * @return string
     */
    public function getText()
    {
        $chars = str_split($this->text);
        $mentions = $this->mentionsAsUser();
        $skipCount = 0;
        $result = [];

        foreach ($chars as $key => $char) {
            if ($skipCount > 0) {
                $skipCount--;
                continue;
            }

            if ($char == '@') {
                foreach ($mentions as $user) {
                    $excerpt = substr($this->text, $key + 1, strlen($user->username));

                    if ($excerpt == $user->username) {
                        array_push($result, "<strong class='text-mention' data-id='{$user->id}'>@{$user->username}</strong>");
                        $skipCount = strlen($user->username);
                        continue;
                    }
                }

                // array_push($result, $char);
            } else {
                array_push($result, $char);
            }
        }

        return implode($result);
    }

    /**
     * Updates the comments mentions using the given comma delimited list of usernames.
     *
     * @return Collection App\Models\Portal\User
     */
    public function updateMentions($list)
    {
        $comment = $this;

        $list = collect(explode(',', $list))->map(function($item) use($comment) {
            $item = trim($item);

            // Remove empty mentions
            if (strlen($item) == 0) return;

            // Make sure mention is still in text
            $in_text = str_contains($comment->text, "@{$item}");
            if (!$in_text) return;

            $user = User::where('username', $item)->first();
            if ($user) return $user;
        });

        MissionCommentMention::where('mission_comment_id', $this->id)->delete();

        foreach ($list as $user) {
            if (!is_null($user)) {
                MissionCommentMention::create([
                    'mission_comment_id' => $this->id,
                    'user_id' => $user->id
                ]);
            }
        }

        return $list;
    }
}
