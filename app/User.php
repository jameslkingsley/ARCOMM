<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Steam;
use Auth;
use App\SteamAPI;

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

    public function registerMediaConversions()
    {
    }

    public function isMember()
    {
        if (Auth::guest()) {
            return false;
        }

        return in_array(
            Steam::convertId($this->steam_id, 'id64'),
            SteamAPI::members()
        );
    }

    public function isAdmin()
    {
        return $this->isMember() && $this->access_level > 1;
    }
}
