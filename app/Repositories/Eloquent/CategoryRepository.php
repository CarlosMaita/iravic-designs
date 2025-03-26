<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use Illuminate\Support\Collection;
use App\Repositories\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{

    /**
     * CategoryRepository constructor.
     *
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de Categorías de productos
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model
            ->orderBy('name')
            ->with('baseCategory')
            ->get();
    }

    /**
     * Retorna listado de Categorías de productos
     * 
     * @return
     */
    public function allQuery()
    {
        return $this->model
            ->orderBy('name')
            ->with('baseCategory');
    }
}