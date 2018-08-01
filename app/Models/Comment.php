<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use App\Traits\AppendableActions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes,
        BelongsToUser,
        AppendableActions;

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get all of the owning commentable models.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Gets the text attribute.
     *
     * @return string
     */
    public function getTextAttribute($value)
    {
        return nl2br($value);
    }
}
