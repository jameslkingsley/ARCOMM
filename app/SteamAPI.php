<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Steam;

class SteamAPI extends Model
{
    public static function members()
    {
        $members = simplexml_load_file('http://steamcommunity.com/groups/ARCOMM/memberslistxml/?xml=1')->members;
        return (array)$members->steamID64;
    }
}
