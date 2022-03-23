<?php

namespace App\Repositories\Eloquent;

use App\Models\Refund;
use Illuminate\Support\Collection;
use App\Repositories\RefundRepositoryInterface;

class RefundRepository extends BaseRepository implements RefundRepositoryInterface
{

    /**
     * RefundRepository constructor.
     *
     * @param Refund $model
     */
    public function __construct(Refund $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de devoluciones
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with(['customer', 'user'])->orderBy('date', 'DESC')->get();
    }
}