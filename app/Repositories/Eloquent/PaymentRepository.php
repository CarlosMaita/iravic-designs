<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use Illuminate\Support\Collection;
use App\Repositories\PaymentRepositoryInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{

    /**
     * PaymentRepository constructor.
     *
     * @param Payment $model
     */
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all($params = null): Collection
    {
        $payments = $this->model->with(['box', 'customer', 'user']);

        if (isset($params['customer'])) {
            $payments->where('customer_id', $params['customer']);
        }

        if (isset($params['box'])) {
            $payments->where('box_id', $params['box']);
        }
        
        return $payments->orderBy('date', 'DESC')->get();
    }
}