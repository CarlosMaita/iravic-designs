<?php

namespace App\Repositories\Eloquent;

use App\Models\RefundProduct;
use Illuminate\Support\Collection;
use App\Repositories\RefundProductRepositoryInterface;

class RefundProductRepository extends BaseRepository implements RefundProductRepositoryInterface
{

    /**
     * RoleRepository constructor.
     *
     * @param RefundProduct $model
     */
    public function __construct(RefundProduct $model)
    {
        parent::__construct($model);
    }
}