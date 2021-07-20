<?php

namespace App\Repositories;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    /**
     * @return Collection
     */
    public function onlyPrincipals(): Collection;

    /**
    * @param $request
    * @return void
    */
    public function createByRequest($request): void;
}