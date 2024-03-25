<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use Illuminate\Support\Collection;
use App\Repositories\CustomerRepositoryInterface;

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
        return $this->model->with('zone')->orderBy('name')->get();
    }
    public function allOnlyName(): Collection{
        return $this->model->select(["id", "name"])->orderBy('name')->get();
    }

    public function allQuery()
    {
        return $this->model->with('zone')->orderBy('name');
    }

    /**
     * Retorna listado de clientes que tienen deudas y necesitan ser avisados/visitados.
     * Cada modelo de cliente tiene un metodo "needsToNotifyDebt" para validar si necesita entrar en este listado
     * 
     * @return Collection
     */
    public function debtorsToNotify(): Collection
    {
        $customers = $this->model->with('zone')
                            ->whereHas('debts')
                            ->orWhereHas('orders', function($q) {
                                $q->where('payed_credit', 1);
                            })
                            ->get();

        return $customers->filter(function ($customer) {
            return $customer->needsToNotifyDebt();
        })->values();
    }

    /**
     * Retorna listado de clientes que se les ha postergado la visita y necesitan ser reagendados. 
     * Cada modelo de cliente tiene un metodo "needsToNotifyDebt" para validar si necesita entrar en este listado
     * 
     * @return Collection
     */
    public function pendingToScheduleToNotify(): Collection
    {
        $customers = $this->model->where('is_pending_to_schedule', 1 )
                            ->with('zone')
                            ->get();

        return $customers;
    }
}