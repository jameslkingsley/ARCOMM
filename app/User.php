<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Steam;
use Auth;
use App\SteamAPI;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'steam_id',
        'username',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function isMember()
    {
        if (Auth::guest()) {
            return false;
        }

        return in_array(
            Steam::convertId(Auth::user()->steam_id, 'id64'),
            SteamAPI::members()
        );
    }

    public static function isAdmin()
    {
        if (!self::isMember()) {
            return false;
        }

        return in_array(
            Steam::convertId(Auth::user()->steam_id, 'id64'),
            explode (",", env('ADMIN_STEAM_ID64', []))
        );
    }
}
