<?php

namespace App\Support;

trait BelongsToMission
{
    /**
     * Gets the mission model.
     *
     * @return App\Models\Missions\Mission
     */
    public function mission()
    {
        return $this->belongsTo('App\Models\Missions\Mission');
    }
}
