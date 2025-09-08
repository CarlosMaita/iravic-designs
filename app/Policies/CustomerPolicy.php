<?php

namespace App\Policies;

use App\Models\Customer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @return mixed
     */
    public function viewAny($user)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        

        return $user->permissions()->contains('view-customer');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function view($user, Customer $customer)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        

        return $user->permissions()->contains('view-customer');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @return mixed
     */
    public function create($user)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        

        return $user->permissions()->contains('create-customer');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function update($user, Customer $customer)
    {   
        // Only admin users (App\User) have permissions, customers don't have access to admin features
   
        if ($user instanceof \App\Models\Customer) {
   
            return false;
   
        }
   
        
   
        return $user->permissions()->contains('update-customer');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function delete($user, Customer $customer)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        

        return $user->permissions()->contains('delete-customer');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function restore($user, Customer $customer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Customer  $customer
     * @return mixed
     */
    public function forceDelete($user, Customer $customer)
    {
        //
    }
}
