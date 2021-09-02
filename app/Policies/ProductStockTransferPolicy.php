<?php

namespace App\Policies;

use App\Models\ProductStockTransfer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductStockTransferPolicy
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
        return $user->permissions()->contains('view-transfer');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductStockTransfer  $productStockTransfer
     * @return mixed
     */
    public function view(User $user, ProductStockTransfer $productStockTransfer)
    {
        return $user->permissions()->contains('view-transfer');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->contains('create-transfer');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductStockTransfer  $productStockTransfer
     * @return mixed
     */
    public function update(User $user, ProductStockTransfer $productStockTransfer)
    {
        
        return $user->permissions()->contains('update-transfer');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductStockTransfer  $productStockTransfer
     * @return mixed
     */
    public function delete(User $user, ProductStockTransfer $productStockTransfer)
    {
        return $user->permissions()->contains('delete-transfer') && !$productStockTransfer->is_accepted;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductStockTransfer  $productStockTransfer
     * @return mixed
     */
    public function restore(User $user, ProductStockTransfer $productStockTransfer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductStockTransfer  $productStockTransfer
     * @return mixed
     */
    public function forceDelete(User $user, ProductStockTransfer $productStockTransfer)
    {
        //
    }
}
