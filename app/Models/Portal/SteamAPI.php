<?php

namespace App\Models\Portal;

use Steam;
use App\Models\Portal\User;
use Illuminate\Database\Eloquent\Model;

class SteamAPI extends Model
{
    /**
     * Gets all Steam group member ID's.
     *
     * @return array
     */
    public static function members()
    {
        try {
            $members = simplexml_load_file('http://steamcommunity.com/groups/ARCOMM/memberslistxml/?xml=1')->members->steamID64;
            return (array)$members;
        } catch(\Exception $error) {
            return User::all()->pluck('steam_id')->all();
        }
    }
}
