<?php

namespace App\Repositories\Eloquent;

use App\Constants\DaysConstants;
use App\Constants\FrequencyCollectionConstants;
use App\Models\Customer;
use Illuminate\Support\Collection;
use App\Repositories\CustomerRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{

    /**
     * CustomerRepository constructor.
     *
     * @param Customer $model
     */
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de clientes
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

    /**
     * Retorna listado de clientes
     * 
     * @return Collection
     */
    public function allQuery()
    {
        return $this->model->orderBy('name');
    }


    /**
     * Retrieve all records with only 'id' and 'name' fields from the model.
     *
     * @return Collection
     */
    public function allOnlyName(): Collection{
        return DB::table($this->model->getTable())
            ->select(['id', 'name'])
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();
    }

 
    /**
     * Retorna listado de clientes que tienen deudas y necesitan ser avisados/visitados.
     * Cada modelo de cliente tiene un metodo "needsToNotifyDebt" para validar si necesita entrar en este listado
     * 
     * @return Collection
     */
    public function debtorsToNotify(): Collection
    {
        $customers = $this->model
                            ->whereHas('debts')
                            ->orWhereHas('orders', function($q) {
                                $q->where('payed_credit', 1);
                            })
                            ->get();

        return $customers->filter(function ($customer) {
            return $customer->needsToNotifyDebt();
        })->values();
    }

    public function debtorsToNotifyQuery()
    {
        return $this->model
            ->whereHas('debts')
            ->orWhereHas('orders', function ( $query) {
                $query->where('payed_credit', true);
            })
            ->get()
            ->filter(fn (Customer $customer) => $customer->needsToNotifyDebt());
    }

    /**
     * Retorna listado de clientes que se les ha postergado la visita y necesitan ser reagendados. 
     * Cada modelo de cliente tiene un metodo "needsToNotifyDebt" para validar si necesita entrar en este listado
     * 
     * @return Collection
     */
    public function pendingToScheduleToNotify(): Collection
    {
        // Return empty collection since scheduling module is removed
        return collect();
    }

    public function pendingToScheduleToNotifyQuery()
    {
        // Return query that returns no results since scheduling module is removed
        return $this->model->whereRaw('1 = 0');
    }

    public function updateVisits( $collection_frequency, $collection_day, $customer_id ){
        // No-op since visits/scheduling module is removed
    }

    public static function setNextDateVisit( $date, $collection_frequency, $collection_day ): Carbon
    {
        // Kept for backwards compatibility but no longer used since scheduling module is removed
        return $date;
    }
}