<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductStockHistory;
use Illuminate\Support\Collection;
use App\Repositories\ProductStockHistoryRepositoryInterface;

class ProductStockHistoryRepository extends BaseRepository implements ProductStockHistoryRepositoryInterface
{
    /**
     * ProductStockHistoryRepository constructor.
     *
     * @param Product $model
     */
    public function __construct(ProductStockHistory $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all($criteria = null): Collection
    {
        $query = $this->model->with(['order_product', 'user']);
        
        if (isset($criteria['product'])) {
            $query->whereProduct($criteria['product']);
        }

        if (isset($criteria['stock_column'])) {
            $query->whereStock($criteria['stock_column']);
        }

        return $query->orderBy('created_at', 'DESC')->get();
    }
}