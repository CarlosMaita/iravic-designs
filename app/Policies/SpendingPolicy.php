<?php

namespace App\Policies;

use App\User;
use App\Models\Spending;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpendingPolicy
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
        return $user->permissions()->contains('view-spending');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Spending  $spending
     * @return mixed
     */
    public function view(User $user, Spending $spending)
    {
        return $user->permissions()->contains('view-spending') && $user->id == $spending->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->contains('view-spending');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Spending  $spending
     * @return mixed
     */
    public function update(User $user, Spending $spending)
    {
        return $user->permissions()->contains('view-spending') && $user->id == $spending->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Spending  $spending
     * @return mixed
     */
    public function delete(User $user, Spending $spending)
    {
        return $user->permissions()->contains('view-spending') && $user->id == $spending->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Spending  $spending
     * @return mixed
     */
    public function restore(User $user, Spending $spending)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \Models\Spending  $spending
     * @return mixed
     */
    public function forceDelete(User $user, Spending $spending)
    {
        //
    }
}
