<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Date attributes.
     *
     * @var array
     */
    protected $dates = [
        'starts_at'
    ];

    /**
     * Gets all associated missions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function missions()
    {
        return $this->hasMany(OperationMission::class);
    }
}
