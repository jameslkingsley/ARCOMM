<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Gets all associated missions.
     *
     * @return \Laravel\Nova\Fields\HasMany
     */
    public function missions()
    {
        return $this->hasMany(Mission::class);
    }
}
