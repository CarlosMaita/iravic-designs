<?php

namespace App\Policies;

use App\Models\Brand;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy
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
        return true; // Original: $user->permissions()->contains('view-brand');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Brand  $brand
     * @return mixed
     */
    public function view($user, Brand $brand)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('view-brand');
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
        return true; // Original: $user->permissions()->contains('create-brand');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Brand  $brand
     * @return mixed
     */
    public function update($user, Brand $brand)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('update-brand');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Brand  $brand
     * @return mixed
     */
    public function delete($user, Brand $brand)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('delete-brand');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Brand  $brand
     * @return mixed
     */
    public function restore($user, Brand $brand)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // No restore logic implemented
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Brand  $brand
     * @return mixed
     */
    public function forceDelete($user, Brand $brand)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // No force delete logic implemented
        return false;
    }
}
