<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    /**
    * @param $id
    * @return Model
    */
    public function all(): Collection;
}