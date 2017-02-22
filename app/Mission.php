<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Map;
use App\User;

class Mission extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'last_played'
    ];

    public function map()
    {
        return Map::find($this->map_id);
    }

    public function user()
    {
        return User::find($this->user_id);
    }
}
