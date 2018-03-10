<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use BelongsToUser;

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Attribute casts.
     *
     * @var array
     */
    protected $casts = [
        'locked_briefings' => 'array'
    ];

    /**
     * Attribute dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'last_played',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'ref';
    }

    /**
     * Gets the ext attribute.
     *
     * @return object
     */
    public function getExtAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Gets the cfg attribute.
     *
     * @return object
     */
    public function getCfgAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Gets the sqm attribute.
     *
     * @return object
     */
    public function getSqmAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Gets the associated map model.
     *
     * @return \App\Models\Map
     */
    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    /**
     * Gets the verified by user model.
     *
     * @return \App\Models\User
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'id', 'verified_by');
    }
}
