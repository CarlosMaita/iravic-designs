<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    /**
     * @return Collection
     */
    public function onlyPrincipals($criteria = null): Collection;

    /**
    * @param $request
    * @return void
    */
    public function createByRequest($request): void;

    /**
    * @param $request
    * @return void
    */
    public function updateByRequest($id, $request): void;

    /**
    * @param $product
    * @param $files
    * @return void
    */
    public function saveImages($product, $files): void;
}