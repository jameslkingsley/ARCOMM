<?php

namespace App\Traits;

use App\Models\Comment;

trait Commentable
{
    /**
     * Gets the comments for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
