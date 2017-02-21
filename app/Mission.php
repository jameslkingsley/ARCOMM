<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Map;
use App\User;

class Mission extends Model
{
    public function map()
    {
        return Map::find($this->map_id);
    }

    public function user()
    {
        return User::find($this->user_id);
    }
}
