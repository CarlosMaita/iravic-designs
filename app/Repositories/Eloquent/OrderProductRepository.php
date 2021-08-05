<?php

namespace App\Repositories\Eloquent;

use App\Models\OrderProduct;
use Illuminate\Support\Collection;
use App\Repositories\OrderProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class OrderProductRepository extends BaseRepository implements OrderProductRepositoryInterface
{

    /**
     * BrandRepository constructor.
     *
     * @param Brand $model
     */
    public function __construct(OrderProduct $model)
    {
        parent::__construct($model);
    }
}