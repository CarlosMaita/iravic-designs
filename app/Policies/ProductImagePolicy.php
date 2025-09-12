<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProductImage;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductImagePolicy
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
        return true; // Original: $user->permissions()->contains('view-products-image');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\odel=Models\ProductImage  $productImage
     * @return mixed
     */
    public function view($user, ProductImage $productImage)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        

        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('view-products-image');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @return mixed
     */
    public function create($user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\odel=Models\ProductImage  $productImage
     * @return mixed
     */
    public function update($user, ProductImage $productImage)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\odel=Models\ProductImage  $productImage
     * @return mixed
     */
    public function delete($user, ProductImage $productImage)
    {
        // Only admin users (App\User) have permissions, customers don't have access to admin features

        if ($user instanceof \App\Models\Customer) {

            return false;

        }

        

        // Note: Permissions system removed in #116 - returning true for admin users
        return true; // Original: $user->permissions()->contains('delete-products-image');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\odel=Models\ProductImage  $productImage
     * @return mixed
     */
    public function restore($user, ProductImage $productImage)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User|\App\Models\Customer  $user
     * @param  \App\odel=Models\ProductImage  $productImage
     * @return mixed
     */
    public function forceDelete($user, ProductImage $productImage)
    {
        //
    }
}
