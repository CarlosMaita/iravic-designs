<?php

namespace App\Repositories\Eloquent;

use App\Models\Visit;
use Illuminate\Support\Collection;
use App\Repositories\VisitRepositoryInterface;

class VisitRepository extends BaseRepository implements VisitRepositoryInterface
{

    /**
     * VisitRepository constructor.
     *
     * @param Visit $model
     */
    public function __construct(Visit $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all($params = null): Collection
    {
        $results = $this->model->with(['schedule', 'customer', 'creator', 'responsable']);

        if (isset($params['customer'])) {
            $results->where('customer_id', $params['customer'])->orderBy('date', 'DESC');
        }

        if (isset($params['schedule'])) {
            $results->where('schedule_id', $params['schedule'])->orderBy('id', 'ASC');
        }
        
        return $results->get();
    }
}