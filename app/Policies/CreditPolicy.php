<?php

namespace App\Policies;

use App\Models\Credit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CreditPolicy
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
        return $user->permissions()->contains('view-credit');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Credit  $credit
     * @return mixed
     */
    public function view(User $user, Credit $credit)
    {
        return $user->permissions()->contains('view-credit');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
       // return $user->permissions()->contains('create-credit');
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Credit  $credit
     * @return mixed
     */
    public function update(User $user, Credit $credit)
    {
        // return $user->permissions()->contains('update-credit');
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\App\Models\Credit  $credit
     * @return mixed
     */
    public function delete(User $user, Credit $credit)
    {
        // return $user->permissions()->contains('delete-credit');
        return false;
    }

    
}
