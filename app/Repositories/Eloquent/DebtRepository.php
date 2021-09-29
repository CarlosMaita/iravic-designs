<?php

namespace App\Repositories\Eloquent;

use App\Models\Debt;
use Illuminate\Support\Collection;
use App\Repositories\DebtRepositoryInterface;

class DebtRepository extends BaseRepository implements DebtRepositoryInterface
{

    /**
     * BrandRepository constructor.
     *
     * @param Brand $model
     */
    public function __construct(Debt $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all($params = null): Collection
    {
        $query = $this->model->with(['customer', 'user']);

        if (isset($params['customer'])) {
            $query->where('customer_id', $params['customer']);
        }

        if (isset($params['box'])) {
            $query->where('box_id', $params['box']);
        }
        
        return $query->orderBy('date', 'DESC')->get();
    }
}