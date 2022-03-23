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
     * @param Permission $model
     */
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de todos los permisos
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->orderBy('display_name')->get();
    }
}