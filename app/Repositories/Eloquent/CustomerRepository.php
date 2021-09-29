<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use Illuminate\Support\Collection;
use App\Repositories\CustomerRepositoryInterface;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{

    /**
     * BrandRepository constructor.
     *
     * @param Brand $model
     */
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with('zone')->orderBy('name')->get();
    }

    /**
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

        return $customers->filter->needsToNotifyDebt()->values();
    }
}