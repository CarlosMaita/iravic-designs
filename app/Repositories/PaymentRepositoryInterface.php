<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface PaymentRepositoryInterface
{
    public function all($params = null): Collection;
}