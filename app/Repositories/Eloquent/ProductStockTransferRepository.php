<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductStockTransfer;
use Illuminate\Support\Collection;
use App\Repositories\ProductStockTransferRepositoryInterface;

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
        $query = $this->model->with(['product', 'creator', 'responsable']);
        
        // if (isset($criteria['product'])) {
        //     $query->whereProduct($criteria['product']);
        // }

        // if (isset($criteria['stock_column'])) {
        //     $query->whereStock($criteria['stock_column']);
        // }

        return $query->orderBy('created_at', 'DESC')->get();
    }
}