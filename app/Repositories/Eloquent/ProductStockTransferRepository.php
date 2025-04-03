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
     * @param ProductStockTransfer $model
     */
    public function __construct(ProductStockTransfer $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de solicitudes de transferencias de stocks filtrando por tipo de Stock del usuario logueado
     * 
     * @return Collection
     */
    public function all($criteria = null): Collection
    {
        $roles_name = Auth::user()->roles->flatten()->pluck('name');
        $query = $this->model->with(['product', 'creator', 'responsable']);
        return $query->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Retorna listado de solicitudes de transferencias de stocks filtrando por tipo de Stock del usuario logueado
     * 
     * @return 
     */
    public function allQuery()
    {
        $roles_name = Auth::user()->roles->flatten()->pluck('name');
        $query = $this->model->with('creator', 'responsable')->with(['product' => fn($q) => $q->withTrashed()]);
        return $query->orderBy('products_stock_transfer.created_at', 'DESC');
    }
}