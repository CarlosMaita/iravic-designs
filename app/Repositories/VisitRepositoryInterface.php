<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface VisitRepositoryInterface
{
    public function all($params = null): Collection;
}