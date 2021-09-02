<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductStockTransfer;
use App\Repositories\ProductStockTransferRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ProductStockTransferRepository extends BaseRepository implements ProductStockTransferRepositoryInterface
{
    /**
     * ProductStockTransferRepository constructor.
     *
     * @param Product $model
     */
    public function __construct(ProductStockTransfer $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all($criteria = null): Collection
    {
        $roles_name = Auth::user()->roles->flatten()->pluck('name');
        $query = $this->model->with(['product', 'creator', 'responsable']);

        if ($roles_name->contains('Camión') || $roles_name->contains('Moto')) {
            $query->whereUserStock(Auth::user()->id, 'stock_truck');
        }

        if ($roles_name->contains('Local')) {
            $query->whereUserStock(Auth::user()->id, 'stock_local');
        }

        return $query->orderBy('created_at', 'DESC')->get();
    }
}