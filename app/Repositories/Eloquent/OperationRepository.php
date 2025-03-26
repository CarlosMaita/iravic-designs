<?php

namespace App\Repositories\Eloquent;

use App\Models\Operation;
use App\Repositories\OperationRepositoryInterface;
use Illuminate\Support\Collection;

class OperationRepository extends BaseRepository implements OperationRepositoryInterface
{

    /**
     * OperationRepository constructor.
     *
     * @param Operation $model
     */
    public function __construct(Operation $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de operaciones (Devoluciones, Deudas, Pagos, Ventas) de un cliente. Se usa para su estado de cuenta
     * 
     * @return Collection
     */
    public function allByCustomer($params): Collection
    {
        return $this->model->with(['customer', 'debt', 'order', 'payment', 'refund'])
                ->where('customer_id', $params['customer'])
                ->orderBy('created_at', 'ASC')
                ->get();
    }
}