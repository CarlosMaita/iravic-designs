<?php

namespace App\Repositories\Eloquent;

use App\Models\Permission;
use Illuminate\Support\Collection;
use App\Repositories\PermissionRepositoryInterface;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{

    /**
     * PermissionRepository constructor.
     *
     * @param Cliente $model
     */
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->orderBy('display_name')->get();
    }
}