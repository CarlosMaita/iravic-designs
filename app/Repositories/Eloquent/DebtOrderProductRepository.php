<?php

namespace App\Repositories\Eloquent;

use App\Models\DebtOrderProduct;
use App\Repositories\DebtOrderProductRepositoryInterface;

class DebtOrderProductRepository extends BaseRepository implements DebtOrderProductRepositoryInterface
{

    /**
     * BrandRepository constructor.
     *
     * @param Brand $model
     */
    public function __construct(DebtOrderProduct $model)
    {
        parent::__construct($model);
    }
}