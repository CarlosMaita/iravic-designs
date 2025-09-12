<?php

namespace App\Policies;

use App\Models\Store;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
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
        return true; // Original: $user->permissions()->contains('view-store');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Store  $store
     * @return mixed
     */
    public function view($user, Store $store)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        

        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('view-store');
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
        return true; // Original: $user->permissions()->contains('create-store');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Store  $store
     * @return mixed
     */
    public function update($user, Store $store)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        

        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('update-store');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Store  $store
     * @return mixed
     */
    public function delete($user, Store $store)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        

        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('delete-store');
    }
}
