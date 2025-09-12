<?php

namespace App\Repositories\Eloquent;

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

}