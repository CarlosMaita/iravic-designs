<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
interface PaymentRepositoryInterface
{
    public function all($customer_id = null): Collection;
}