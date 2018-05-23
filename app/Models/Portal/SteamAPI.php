<?php

namespace App\Models\Portal;

use Steam;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class SteamAPI extends Model
{
    /**
     * Gets all Steam group member ID's.
     *
     * @return array
     */
    public static function members(bool $fresh = false)
    {
        if ($fresh) {
            Cache::forget('steam_members');
        }

        return Cache::remember('steam_members', 60, function () {
            try {
                $members = simplexml_load_file('http://steamcommunity.com/groups/ARCOMM/memberslistxml/?xml=1')->members->steamID64;

                return (array)$members;
            } catch (\Exception $error) {
                return User::all()->pluck('steam_id')->all();
            }
        });
    }
}
