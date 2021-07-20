<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use Illuminate\Support\Collection;
use App\Repositories\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{

    /**
     * BrandRepository constructor.
     *
     * @param Category $model
     */
    public function __construct(Category $model)
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