<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface OperationRepositoryInterface
{
    public function allByCustomer($params): Collection;
}