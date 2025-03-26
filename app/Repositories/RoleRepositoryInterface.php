<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    /**
    * @param $id
    * @return Model
    */
    public function all($except_role = null): Collection;

    /**
     * @return Collection of all roles Employees
     */
    public function allEmployees(): Collection;

    /**
     * @return Collection of all roles not Employees
     */
    public function allNotEmployees(): Collection;
}