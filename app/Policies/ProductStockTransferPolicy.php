<?php

namespace App\Policies;

use App\Models\ProductStockTransfer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductStockTransferPolicy
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
        return true; // Original: $user->permissions()->contains('view-transfer');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\ProductStockTransfer  $productStockTransfer
     * @return mixed
     */
    public function view($user, ProductStockTransfer $productStockTransfer)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        

        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('view-transfer');
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
        return true; // Original: $user->permissions()->contains('create-transfer');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\ProductStockTransfer  $productStockTransfer
     * @return mixed
     */
    public function update($user, ProductStockTransfer $productStockTransfer)
    {
        
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        
        if ($user instanceof \App\Models\Customer) {

        
            return false;

        
        }

        
        

        
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('update-transfer');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\ProductStockTransfer  $productStockTransfer
     * @return mixed
     */
    public function delete($user, ProductStockTransfer $productStockTransfer)
    {
        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('delete-transfer') && !$productStockTransfer->is_accepted;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\ProductStockTransfer  $productStockTransfer
     * @return mixed
     */
    public function restore($user, ProductStockTransfer $productStockTransfer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\Models\ProductStockTransfer  $productStockTransfer
     * @return mixed
     */
    public function forceDelete($user, ProductStockTransfer $productStockTransfer)
    {
        //
    }
}
