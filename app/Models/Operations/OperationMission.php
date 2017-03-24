<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;

class OperationMission extends Model
{
    /**
     * Gets the mission.
     *
     * @return App\Models\Missions\Mission
     */
    public function mission()
    {
        return $this->belongsTo('App\Models\Missions\Mission');
    }

    /**
     * Gets the operation.
     *
     * @return App\Models\Operations\Operation
     */
    public function operation()
    {
        return $this->belongsTo('App\Models\Operations\Operation');
    }
}
