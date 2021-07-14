<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Boolean;

/**
* Interface EloquentRepositoryInterface
* @package App\Repositories
*/
interface EloquentRepositoryInterface
{
   /**
    * @param $id
    * @return Model
    */
   public function all(): Collection;

   /**
    * @param array $attributes
    * @return Model
    */
   public function create(array $attributes): Model;

   /**
    * @param $id
    * @return Model
    */
   public function find($id): ?Model;

   /**
    * @param $id
    * @param array $attributes
    * @return Boolean
    */
    public function update($id, array $attributes): bool;

    /**
    * @param $id
    * @return Boolean
    */
    public function delete($id): bool;
}