<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperationMission extends Model
{
    /**
     * Gets the mission.
     *
     * @return App\Mission
     */
    public function mission()
    {
        return $this->belongsTo('App\Mission');
    }

    /**
     * Gets the operation.
     *
     * @return App\Operation
     */
    public function operation()
    {
        return $this->hasOne('App\Operation');
    }
}
