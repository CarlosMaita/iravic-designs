<?php

namespace App\Repositories\Eloquent;

use App\Models\Zone;
use Illuminate\Support\Collection;
use App\Repositories\ZoneRepositoryInterface;

class ZoneRepository extends BaseRepository implements ZoneRepositoryInterface
{

    /**
     * UserRepository constructor.
     *
     * @param Zone $model
     */
    public function __construct(Zone $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de zonas ordenadas por posicion
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->orderBy('position')->get();
    }
}