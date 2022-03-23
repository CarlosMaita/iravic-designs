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
     * @param Brand $model
     */
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de marcas de productos
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->orderBy('name')->get();
    }
}