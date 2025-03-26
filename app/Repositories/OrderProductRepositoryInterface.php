<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface OrderProductRepositoryInterface
{
    /**
     * 
     */
    public function availableForRefund($customer_id): Collection;
}