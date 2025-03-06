<?php

namespace App\Repositories\Eloquent;

use App\Models\Store;
use Illuminate\Support\Collection;
use App\Repositories\StoreRepositoryInterface;

class StoreRepository extends BaseRepository implements StoreRepositoryInterface
{

    /**
     * StoreRepository constructor.
     *
     * @param Store $model
     */
    public function __construct(Store $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de depositos 
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->allQuery()->get();
    }

    /**
     * Retorna listado de Depositos 
     * 
     * @return
     */
    public function allQuery()
    {
        return $this->model
            ->with('type')
            ->orderBy('name');
    }

    /**
     * Crea un nuevo Deposito
     * 
     * @param array $data
     * @return Store
     */ 
    public function create(array $data): Store
    {
        return $this->model->create($data);
    }

   
}