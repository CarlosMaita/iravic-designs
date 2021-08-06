<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use Illuminate\Support\Collection;
use App\Repositories\PaymentRepositoryInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{

    /**
     * BrandRepository constructor.
     *
     * @param Brand $model
     */
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all($customer_id = null): Collection
    {
        $payments = $this->model->with(['box', 'user']);

        if ($customer_id) {
            $payments->where('customer_id', $customer_id);
        }
        
        return $payments->orderBy('date', 'DESC')->get();
    }
}