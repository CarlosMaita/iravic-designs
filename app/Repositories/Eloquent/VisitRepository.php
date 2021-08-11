<?php

namespace App\Repositories\Eloquent;

use App\Models\Visit;
use App\Repositories\VisitRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    /**
     * @return Collection
     */
    public function allBySchedule($schedule_id): Collection
    {
        return $this->model->select('visits.*')
                            ->with(['customer.zone', 'responsable'])
                            ->join('customers', 'customers.id', '=', 'visits.customer_id')
                            ->join('zones', 'zones.id', '=', 'customers.zone_id')
                            ->where('visits.schedule_id', $schedule_id)
                            ->orderBy('zones.name')
                            ->get();
    }

}