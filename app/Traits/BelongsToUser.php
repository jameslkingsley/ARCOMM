<?php

namespace App\Traits;

use App\Models\User;

trait BelongsToUser
{
    /**
     * Gets the associated user model.
     *
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
