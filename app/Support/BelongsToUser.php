<?php

namespace App\Support;

use App\Models\Portal\User;

trait BelongsToUser
{
    /**
     * Gets the user model.
     *
     * @return App\Models\Portal\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
