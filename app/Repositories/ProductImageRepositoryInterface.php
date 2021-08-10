<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface ProductImageRepositoryInterface
{
    public function all($product_id = null): Collection;
}