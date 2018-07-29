<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use App\Traits\AppendableActions;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use BelongsToUser,
        AppendableActions;

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

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
