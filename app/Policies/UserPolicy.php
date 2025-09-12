<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @return mixed
     */
    public function viewAny($user)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view($user, User $model)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @return mixed
     */
    public function create($user)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $authenticatedUser, User $user)
    {
        // Note: Roles and permissions system has been removed as per issue #116
        // Returning true to allow updates - implement alternative authorization if needed
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $authenticatedUser, User $user)
    {
        // Note: Permissions system removed in #116 - returning true to allow deletions
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore($user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete($user, User $model)
    {
        //
    }
}
