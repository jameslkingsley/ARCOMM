<?php

namespace App\Traits;

trait AlwaysAllowAdmin
{
    /**
     * Allows the admin to do everything.
     *
     * @return boolean
     */
    public function before($user, $ability)
    {
        if ($user->hasRole('administrator')) {
            return true;
        }
    }
}
