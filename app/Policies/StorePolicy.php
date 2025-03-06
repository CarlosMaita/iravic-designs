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
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->permissions()->contains('view-store');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Store  $store
     * @return mixed
     */
    public function view(User $user, Store $store)
    {
        return $user->permissions()->contains('view-store');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->contains('create-store');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Store  $store
     * @return mixed
     */
    public function update(User $user, Store $store)
    {
        return $user->permissions()->contains('update-store');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Store  $store
     * @return mixed
     */
    public function delete(User $user, Store $store)
    {
        return $user->permissions()->contains('delete-store');
    }
}
