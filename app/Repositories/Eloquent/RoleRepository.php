<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use Illuminate\Support\Collection;
use App\Repositories\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{

    /**
     * RoleRepository constructor.
     *
     * @param Cliente $model
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all($except_role = null): Collection
    {
        $roles = $this->model->orderBy('name');

        if ($except_role) {
            $roles->whereNotName($except_role);
        }

        return $roles->get();
    }

    public function allEmployees(): Collection
    {
        return $this->model
                    ->whereEmployee()
                    ->whereNotSuperadmin()
                    ->orderBy('name')
                    ->get();
    }
}