<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
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
        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('view-product');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function view($user, Product $product)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('view-product');
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
        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('create-product');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function update($user, Product $product)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('update-product');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function delete($user, Product $product)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('delete-product');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function restore($user, Product $product)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // No restore logic implemented
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function forceDelete($user, Product $product)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // No force delete logic implemented
        return false;
    }


    /**
     * Determine whether the user can access prices per method payment.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @return mixed
     */

    public function pricesPerMethodPayment($user)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features
        if ($user instanceof \App\Models\Customer) {
            return false;
        }
        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('prices-per-method-payment');
    }
}
