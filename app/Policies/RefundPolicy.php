<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Refund;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefundPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->permissions()->contains('view-refund');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Refund  $refund
     * @return mixed
     */
    public function view($user, Refund $refund)
    {
        if( $user instanceof Customer) return true;
        return $user->permissions()->contains('view-refund');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->contains('create-refund');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Refund  $refund
     * @return mixed
     */
    public function update($user, Refund $refund)
    {
        if( $user instanceof Customer) return false;
        return $user->permissions()->contains('update-refund');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Refund  $refund
     * @return mixed
     */
    public function delete($user, Refund $refund)
    {
        if( $user instanceof Customer) return false;
        return $user->permissions()->contains('delete-refund');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Refund  $refund
     * @return mixed
     */
    public function restore(User $user, Refund $refund)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Refund  $refund
     * @return mixed
     */
    public function forceDelete(User $user, Refund $refund)
    {
        //
    }
}
