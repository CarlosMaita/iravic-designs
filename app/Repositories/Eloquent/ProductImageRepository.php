<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductImage;
use Illuminate\Support\Collection;
use App\Repositories\ProductImageRepositoryInterface;
use App\Services\Images\ImageService;

class ProductImageRepository extends BaseRepository implements ProductImageRepositoryInterface
{
    /**
     * ProductImageRepository constructor.
     *
     * @param ProductImage $model
     */
    public function __construct(ProductImage $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de imagenes de un producto
     * 
     * @return Collection
     */
    public function all($product_id = null): Collection
    {
        return $this->model->where('product_id', $product_id)->get();
    }
}