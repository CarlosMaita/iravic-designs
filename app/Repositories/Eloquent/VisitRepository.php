<?php

namespace App\Repositories\Eloquent;

use App\Models\Visit;
use App\Repositories\VisitRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Double;

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
     * Retorna listado de visitas. Puede filtrar por cliente o por agenda
     * 
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
     * Retorna listado de una agenda, filtrando por zonas o roles de usuarios que sean responsables
     * 
     * @return Collection
     */
    public function allBySchedule($schedule_id, $zones = null, $roles = null): Collection
    {
        $user = Auth::user();
        $user_roles = $user->roles->flatten()->pluck('name');

        $query = $this->model->select('visits.*')
                            ->with(['customer.zone', 'responsable']);

        $query->joinCustomers();
        $query->joinZones();
        $query->whereScheduleId($schedule_id);

        if ($roles) {
            $roles_name = explode(',', $roles);
            $query->whereResponsableByRoleName($roles_name);
        }

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
     * Actualiza visita(s) de un cliente para una fecha especifica
     */
    public function completeByDateUser($customer_id, $date): bool
    {
        return $this->model->where('customer_id', $customer_id)
                            ->whereDate('date', $date)
                            ->update(['is_completed' => 1]);
    }

    /**
     * Retorna cantidad (1) si un cliente tiene visita para una fecha
     * 
     * @return int
     */
    public function hasCustomerVisitForDate($date, $customer_id): int
    {
        return $this->model->whereHas('customer', function($q) use ($customer_id) {
                            $q->where('id', $customer_id);
                        })
                        ->whereDate('date', $date)
                        ->count();
    }

    /**
     * Retorna cantidad de visitas de clientes de una fecha y zona especifica 
     * 
     * @return int
     */
    public function getCountCustomersFromZone($date, $customer_id, $zone_id): int
    {
        return $this->model->whereHas('customer', function($q) use ($customer_id, $zone_id) {
                            $q->where('zone_id', $zone_id)
                                ->where('id', '<>', $customer_id);
                        })
                        ->whereDate('date', $date)
                        ->count();
    }

    public function removeVisitsOfCollection($customer_id)
    {
        // remover visitas que son de cobro, y que son mayores a la fecha actual
        $this->model->where('customer_id', $customer_id)
                    ->where('is_collection', 1)
                    ->where('date', '>', now())
                    ->delete();
    }
    public function updateCollectionsInFutureVisits(int $customerId, float $amountRefund): void
    {
        $futureVisitsCount = $this->countFutureVisitsCollection($customerId);
        $adjustedQuote = $this->calculateQuota($amountRefund, $futureVisitsCount);
        
        $this->reduceQuotaInFutureVisits($customerId, $adjustedQuote);
        // dd( $adjustedQuote);
    }

    private function calculateQuota( float $amountRefund, float $countFutureVisits) : float
    {
        return $countFutureVisits == 0 ? 0 :  $amountRefund / $countFutureVisits;
    }  
    
    private function countFutureVisitsCollection(int $customerId): int
    {
        return $this->model->where('customer_id', $customerId)
                            ->where('is_collection', 1)
                            ->where('date', '>', now())
                            ->count();
    }
   
    private function reduceQuotaInFutureVisits(int $customer_id, $quoteAdjusted){
        $this->model->where('customer_id', $customer_id)
                    ->where('is_collection', 1)
                    ->where('date', '>', now())
                    ->decrement('suggested_collection', $quoteAdjusted);
    }

}