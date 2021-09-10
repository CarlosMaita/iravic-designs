<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Config;

class ConfigPolicy
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
        return $user->permissions()->contains('view-configuration');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Config  $config
     * @return mixed
     */
    public function view(User $user, Config $config)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->contains('create-configuration');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Config  $config
     * @return mixed
     */
    public function update(User $user, Config $config)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Config  $config
     * @return mixed
     */
    public function delete(User $user, Config $config)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Config  $config
     * @return mixed
     */
    public function restore(User $user, Config $config)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Config  $config
     * @return mixed
     */
    public function forceDelete(User $user, Config $config)
    {
        //
    }
}
