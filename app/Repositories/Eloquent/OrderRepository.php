<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use Illuminate\Support\Collection;
use App\Repositories\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{

    /**
     * OrderRepository constructor.
     *
     * @param Order $model
     */
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de todos las ventas de todos los clientes
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with(['customer', 'user'])->orderBy('date', 'DESC')->get();
    }
}