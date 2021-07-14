<?php

namespace App\Repositories\Eloquent;

use App\Models\Brand;
use Illuminate\Support\Collection;
use App\Repositories\BrandRepositoryInterface;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{

    /**
     * BrandRepository constructor.
     *
     * @param Cliente $model
     */
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->orderBy('name')->get();
    }
}