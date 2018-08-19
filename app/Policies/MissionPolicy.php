<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mission;
use Illuminate\Auth\Access\HandlesAuthorization;

class MissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the mission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mission  $mission
     * @return mixed
     */
    public function view(User $user, Mission $mission)
    {
        return $mission->user->is($user)
            || $user->tokenCan('administrate')
            || $user->tokenCan('mission-test')
            || (bool) $mission->verified_by;
    }

    /**
     * Determine whether the user can create missions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the mission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mission  $mission
     * @return mixed
     */
    public function update(User $user, Mission $mission)
    {
        return $mission->user->is($user)
            || $user->tokenCan('administrate')
            || $user->tokenCan('mission-test');
    }

    /**
     * Determine whether the user can delete the mission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mission  $mission
     * @return mixed
     */
    public function delete(User $user, Mission $mission)
    {
        // TODO Prevent deletion if mission is assigned to an operation

        return $mission->user->is($user)
            || $user->tokenCan('administrate');
    }

    /**
     * Determine whether the user can restore the mission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mission  $mission
     * @return mixed
     */
    public function restore(User $user, Mission $mission)
    {
        return $user->tokenCan('administrate');
    }

    /**
     * Determine whether the user can permanently delete the mission.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mission  $mission
     * @return mixed
     */
    public function forceDelete(User $user, Mission $mission)
    {
        return $user->tokenCan('administrate');
    }

    /**
     * Determine whether the user can view the comment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Mission  $mission
     * @return mixed
     */
    public function verify(User $user, Mission $mission)
    {
        // TODO Only if user is tester or admin

        return $user->tokenCan('administrate')
            || $user->tokenCan('mission-test');
    }
}
