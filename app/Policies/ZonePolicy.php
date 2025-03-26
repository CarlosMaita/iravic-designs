<?php

namespace App\Policies;

use App\Models\Zone;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ZonePolicy
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
        return $user->permissions()->contains('view-zone');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Zone  $zone
     * @return mixed
     */
    public function view(User $user, Zone $zone)
    {
        return $user->permissions()->contains('view-zone');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->contains('create-zone');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Zone  $zone
     * @return mixed
     */
    public function update(User $user, Zone $zone)
    {
        return $user->permissions()->contains('update-zone');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Zone  $zone
     * @return mixed
     */
    public function delete(User $user, Zone $zone)
    {
        return $user->permissions()->contains('delete-zone');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Zone  $zone
     * @return mixed
     */
    public function restore(User $user, Zone $zone)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Zone  $zone
     * @return mixed
     */
    public function forceDelete(User $user, Zone $zone)
    {
        //
    }
}
