<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Application;
use App\Traits\AlwaysAllowAdmin;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
{
    use AlwaysAllowAdmin,
        HandlesAuthorization;

    /**
     * Determine whether the user can view the application.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return mixed
     */
    public function view(User $user, Application $application)
    {
        //
    }

    /**
     * Determine whether the user can create applications.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the application.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return mixed
     */
    public function update(User $user, Application $application)
    {
        //
    }

    /**
     * Determine whether the user can delete the application.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return mixed
     */
    public function delete(User $user, Application $application)
    {
        //
    }

    /**
     * Determine whether the user can restore the application.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return mixed
     */
    public function restore(User $user, Application $application)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the application.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return mixed
     */
    public function forceDelete(User $user, Application $application)
    {
        //
    }
}
