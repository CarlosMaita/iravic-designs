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
     * @param Role $model
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de roles
     * 
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

    /**
     * Retorma Listado de todos los usuarios que tengan rol de empleado No superadmin
     * 
     * @return Collection
     */
    public function allEmployees(): Collection
    {
        return $this->model
                    ->whereEmployee()
                    ->whereNotSuperadmin()
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Retorna Listado de todos los usuarios que no sean empleados 
     * 
     * @return Collection
     */
    public function allNotEmployees(): Collection
    {
        return $this->model
                    ->whereNotEmployee()
                    ->orderBy('name')
                    ->get();
    }
}