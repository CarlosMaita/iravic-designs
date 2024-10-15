<?php

namespace App\Repositories\Eloquent;

use App\Models\Collection;
use App\Repositories\CollectionRepositoryInterface;

class CollectionRepository extends BaseRepository implements CollectionRepositoryInterface
{

    /**
     * CollectionRepository constructor.
     *
     * @param Collection $model
     */
    public function __construct(Collection $model)
    {
        parent::__construct($model);
    }


    /**
     * create a new collection
     * 
     * @param array $attributes
     * @return Collection
     * 
     * */
    public function create(array $attributes) : Collection
    {
        return $this->model->create($attributes);
    }

   
}