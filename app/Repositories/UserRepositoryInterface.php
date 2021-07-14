<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
    * @param $id
    * @return Model
    */
    public function allEmployees(): Collection;

    /**
    * @param array $attributes
    * @return Model
    */
    public function updateOrCreateByEmail(array $attributes): Object;
}