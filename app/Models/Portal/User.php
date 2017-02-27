<?php

namespace App\Models\Portal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Models\Missions\Mission;
use App\Models\Portal\SteamAPI;
use Steam;
use Auth;

class User extends Authenticatable implements HasMediaConversions
{
    use Notifiable;
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'steam_id',
        'username',
        'avatar',
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Media library image conversions.
     *
     * @return void
     */
    public function registerMediaConversions() {}

    /**
     * Checks whether the user is a member of the Steam group.
     *
     * @return boolean
     */
    public function isMember()
    {
        if (auth()->guest()) {
            return false;
        }

        return in_array(
            Steam::convertId($this->steam_id, 'id64'),
            SteamAPI::members()
        );
    }

    /**
     * Checks whether the user is an admin.
     * Access level must be greater than 1.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->isMember() && $this->access_level > 1;
    }


    /**
     * Gets the user's missions.
     * Ordered from latest to oldest.
     *
     * @return Collection App\Models\Missions\Mission
     */
    public function missions()
    {
        return Mission::where('user_id', $this->id)->orderBy('created_at', 'desc')->get();
    }
}
