<?php

namespace App\Policies;

use App\Models\Debt;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DebtPolicy
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
        return $user->permissions()->contains('view-debt');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Debt  $debt
     * @return mixed
     */
    public function view(User $user, Debt $debt)
    {
        return $user->permissions()->contains('view-debt');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->contains('create-debt');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Debt  $debt
     * @return mixed
     */
    public function update(User $user, Debt $debt)
    {
        return $user->permissions()->contains('update-debt');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Debt  $debt
     * @return mixed
     */
    public function delete(User $user, Debt $debt)
    {
        return $user->permissions()->contains('delete-debt');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Debt  $debt
     * @return mixed
     */
    public function restore(User $user, Debt $debt)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Debt  $debt
     * @return mixed
     */
    public function forceDelete(User $user, Debt $debt)
    {
        //
    }
}
