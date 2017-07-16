<?php

namespace App\Support;

trait BelongsToUser
{
    /**
     * Gets the user model.
     *
     * @return App\Models\Portal\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Portal\User');
    }
}
