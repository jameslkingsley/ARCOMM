<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'steam_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Determines if the user is a member of the Steam group.
     *
     * @return boolean
     */
    public function member()
    {
        return in_array($this->steam_id, Cache::remember('members', 60, function () {
            try {
                $members = simplexml_load_file(
                    'http://steamcommunity.com/groups/' .
                    config('steam-auth.group') .
                    '/memberslistxml/?xml=1'
                )->members->steamID64;

                return (array) $members;
            } catch (\Exception $error) {
                return static::all()->pluck('steam_id')->all();
            }
        }));
    }
}
