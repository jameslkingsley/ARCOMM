<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Model;
use Steam;

class SteamAPI extends Model
{
    /**
     * Gets all Steam group member ID's.
     *
     * @return array
     */
    public static function members()
    {
        $members = simplexml_load_file('http://steamcommunity.com/groups/ARCOMM/memberslistxml/?xml=1')->members->steamID64;
        return (array)$members;
    }
}
