<?php

namespace App\Policies;

use App\Models\SpecialOffer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecialOfferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny($user)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }

        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('view-special-offers') || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\SpecialOffer  $specialOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view($user, SpecialOffer $specialOffer)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create($user)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }

        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('create-special-offers') || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\SpecialOffer  $specialOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update($user, SpecialOffer $specialOffer)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }

        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('update-special-offers') || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\SpecialOffer  $specialOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete($user, SpecialOffer $specialOffer)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }

        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('delete-special-offers') || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\SpecialOffer  $specialOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore($user, SpecialOffer $specialOffer)
    {
        return $this->delete($user, $specialOffer);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\SpecialOffer  $specialOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete($user, SpecialOffer $specialOffer)
    {
        return $this->delete($user, $specialOffer);
    }
}
