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
     * @param Cliente $model
     */
    public function __construct(Zone $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->orderBy('name')->get();
    }
}