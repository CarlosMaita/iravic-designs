<?php

namespace App\Repositories\Eloquent;

use App\Models\Spending;
use Illuminate\Support\Collection;
use App\Repositories\SpendingRepositoryInterface;

class SpendingRepository extends BaseRepository implements SpendingRepositoryInterface
{

    /**
     * PaymentRepository constructor.
     *
     * @param Payment $model
     */
    public function __construct(Spending $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all($params = null): Collection
    {
        $query = $this->model->with(['user']);

        if (isset($params['box'])) {
            $query->where('box_id', $params['box']);
        }
        
        return $query->orderBy('date', 'DESC')->get();
    }
}