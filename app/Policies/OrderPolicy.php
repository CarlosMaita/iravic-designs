<?php

namespace App\Policies;

use App\Models\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
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
        return $user->permissions()->contains('view-order');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Order  $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        return $user->permissions()->contains('view-order');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->contains('create-order');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Order  $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        // return $user->permissions()->contains('update-order');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Order  $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        // return $user->permissions()->contains('delete-order');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Order  $order
     * @return mixed
     */
    public function restore(User $user, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Order  $order
     * @return mixed
     */
    public function forceDelete(User $user, Order $order)
    {
        //
    }
}
