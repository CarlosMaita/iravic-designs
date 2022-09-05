<?php

namespace App\Policies;

use App\User;
use App\Models\ProductImage;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductImagePolicy
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
        return $user->permissions()->contains('view-products-image');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\odel=Models\ProductImage  $productImage
     * @return mixed
     */
    public function view(User $user, ProductImage $productImage)
    {
        return $user->permissions()->contains('view-products-image');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\odel=Models\ProductImage  $productImage
     * @return mixed
     */
    public function update(User $user, ProductImage $productImage)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\odel=Models\ProductImage  $productImage
     * @return mixed
     */
    public function delete(User $user, ProductImage $productImage)
    {
        return $user->permissions()->contains('delete-products-image');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\odel=Models\ProductImage  $productImage
     * @return mixed
     */
    public function restore(User $user, ProductImage $productImage)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\odel=Models\ProductImage  $productImage
     * @return mixed
     */
    public function forceDelete(User $user, ProductImage $productImage)
    {
        //
    }
}
