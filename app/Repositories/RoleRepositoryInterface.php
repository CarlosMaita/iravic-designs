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
     * 
     */
    public function allEmployees(): Collection;
}