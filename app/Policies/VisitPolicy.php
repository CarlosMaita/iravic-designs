<?php

namespace App\Policies;

use App\Models\Visit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitPolicy
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
        return $user->permissions()->contains('view-visit');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Visit  $visit
     * @return mixed
     */
    public function view(User $user, Visit $visit)
    {
        return $user->permissions()->contains('view-visit');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->contains('create-visit');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Visit  $visit
     * @return mixed
     */
    public function update(User $user, Visit $visit)
    {
        return $user->permissions()->contains('update-visit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Visit  $visit
     * @return mixed
     */
    public function delete(User $user, Visit $visit)
    {
        return $user->permissions()->contains('delete-visit');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Visit  $visit
     * @return mixed
     */
    public function restore(User $user, Visit $visit)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Visit  $visit
     * @return mixed
     */
    public function forceDelete(User $user, Visit $visit)
    {
        //
    }

    /**
     * Determine whether the user can set the visit as completed (Visited)
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function complete(User $user)
    {
        return $user->permissions()->contains('complete-visit');
    }
    
    /**
     * Determine whether the user can update the visit responsable
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function updateResponsable(User $user)
    {
        return $user->permissions()->contains('update-responsable-visit');
    }
}
