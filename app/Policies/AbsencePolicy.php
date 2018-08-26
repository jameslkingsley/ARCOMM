<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Absence;
use App\Traits\AlwaysAllowAdmin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbsencePolicy
{
    use AlwaysAllowAdmin,
        HandlesAuthorization;

    /**
     * Determine whether the user can view the absence.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Absence  $absence
     * @return mixed
     */
    public function view(User $user, Absence $absence)
    {
        //
    }

    /**
     * Determine whether the user can create absences.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the absence.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Absence  $absence
     * @return mixed
     */
    public function update(User $user, Absence $absence)
    {
        return $absence->user->is($user);
    }

    /**
     * Determine whether the user can delete the absence.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Absence  $absence
     * @return mixed
     */
    public function delete(User $user, Absence $absence)
    {
        return $absence->user->is($user);
    }

    /**
     * Determine whether the user can restore the absence.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Absence  $absence
     * @return mixed
     */
    public function restore(User $user, Absence $absence)
    {
        return $absence->user->is($user);
    }

    /**
     * Determine whether the user can permanently delete the absence.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Absence  $absence
     * @return mixed
     */
    public function forceDelete(User $user, Absence $absence)
    {
        return $absence->user->is($user);
    }
}
