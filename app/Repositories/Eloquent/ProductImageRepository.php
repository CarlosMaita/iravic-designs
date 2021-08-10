<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductImage;
use Illuminate\Support\Collection;
use App\Repositories\ProductImageRepositoryInterface;

class ProductImageRepository extends BaseRepository implements ProductImageRepositoryInterface
{
    /**
     * ProductRepository constructor.
     *
     * @param Product $model
     */
    public function __construct(ProductImage $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all($product_id = null): Collection
    {
        return $this->model->where('product_id', $product_id)->get();
    }
}