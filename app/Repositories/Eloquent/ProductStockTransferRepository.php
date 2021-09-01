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
}