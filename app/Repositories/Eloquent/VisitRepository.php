<?php

namespace App\Repositories\Eloquent;

use App\Models\Visit;
use App\Repositories\VisitRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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
    public function allBySchedule($schedule_id, $zones = null): Collection
    {
        $user = Auth::user();
        $user_roles = $user->roles->flatten()->pluck('name');

        $query = $this->model->select('visits.*')
                            ->with(['customer.zone', 'responsable']);

        $query->joinCustomers();
        $query->joinZones();
        $query->whereScheduleId($schedule_id);

        if ($zones) {
            $zones_id = explode(',', $zones);
            $query->whereInZone($zones_id);
        }

        if (!$user_roles->contains('superadmin') && !$user_roles->contains('admin')) {
            $query->whereUserResponsableId($user->id);
        }

        return $query->orderBy('zones.name')->get();
    }

    /**
     * 
     */
    public function completeByDateUser($customer_id, $date): bool
    {
        return $this->model->where('customer_id', $customer_id)
                            ->whereDate('date', $date)
                            ->update(['is_completed' => 1]);
    }

}