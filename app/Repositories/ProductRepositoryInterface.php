<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    /**
    * @param $request
    * @return void
    */
    public function createByRequest($request): void;
}