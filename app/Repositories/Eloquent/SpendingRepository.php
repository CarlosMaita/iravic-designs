<?php

namespace App\Repositories\Eloquent;

use App\Models\Spending;
use Illuminate\Support\Collection;
use App\Repositories\SpendingRepositoryInterface;

class SpendingRepository extends BaseRepository implements SpendingRepositoryInterface
{

    /**
     * PaymentRepository constructor.
     *
     * @param Spending $model
     */
    public function __construct(Spending $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de gastos de todos los usuarios. Puede filtrar por caja que le pertenece a un usuario
     * 
     * @return Collection
     */
    public function all($params = null): Collection
    {
        $query = $this->model->with(['user', 'box']);

        if (isset($params['box'])) {
            $query->where('box_id', $params['box']);
        }
        
        return $query->orderBy('date', 'DESC')->get();
    }
}