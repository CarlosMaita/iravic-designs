<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use Illuminate\Support\Collection;
use App\Repositories\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{

    /**
     * ProductRepository constructor.
     *
     * @param Product $model
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with(['brand', 'category'])->orderBy('name')->get();
    }

    /**
     * @return Collection
     */
    public function onlyPrincipals(): Collection
    {
        return $this->model->doesntHave('product_parent')->with(['brand', 'category'])->orderBy('name')->get();
    }

    /**
    * @param $request
    * @return void
    */
    public function createByRequest($request): void
    {
        $data = $request->only('brand_id', 'category_id', 'code', 'gender', 'is_regular', 'name');

        if (isset($request->is_regular) && $request->is_regular) {
            $data = array_merge(
                $data,
                $request->only('color_id', 'size_id', 'stock_depot', 'stock_local', 'stock_truck')
            );
        }

        $product = $this->create($data);

        if (!isset($request->is_regular) && isset($request->combinations) && count($request->combinations)) {
            foreach ($request->combinations as $combination) {
                $data = array_merge(
                    array('product_id' => $product->id),
                    array('color_id' => $request->colors[$combination]),
                    array('size_id' => $request->sizes[$combination]),
                    array('stock_depot' => $request->stocks_depot[$combination]),
                    array('stock_local' => $request->stocks_local[$combination]),
                    array('stock_truck' => $request->stocks_truck[$combination]),
                    $request->only('brand_id', 'category_id', 'code', 'gender', 'name')
                );
    
                $this->create($data);
            }
        }
    }

    /**
    * @param $request
    * @return void
    */
    public function updateByRequest($id, $request): void
    {
        $data = $request->only('brand_id', 'category_id', 'code', 'gender', 'is_regular', 'name');

        if (isset($request->is_regular) && $request->is_regular) {
            $data = array_merge(
                $data,
                $request->only('color_id', 'size_id', 'stock_depot', 'stock_local', 'stock_truck')
            );
        }

        $model = $this->model->find($id);
        $model->update($data);

        if ($model->is_regular && $model->product_combinations->count()) {
            $model->product_combinations()->delete();
        } else if (!$model->is_regular && isset($request->combinations) && count($request->combinations)) {
            // update combinations
        }
    }
}