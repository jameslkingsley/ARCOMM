<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use BelongsToUser;

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
}
