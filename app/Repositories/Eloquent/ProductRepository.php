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
        $attributes = $request->only('brand_id', 'category_id', 'code', 'gender', 'is_regular', 'name');

        if (isset($request->is_regular) && $request->is_regular) {
            $attributes = array_merge(
                $attributes,
                $request->only('color_id', 'stock_depot', 'stock_local', 'stock_truck')
            );
        }

        $product = $this->create($attributes);

        // Create new combinations
        if ((!isset($request->is_regular) || !$request->is_regular )) {
            if (isset($request->combinations) && count($request->combinations)) {
                foreach ($request->combinations as $combination) {
                    $attributes = array_merge(
                        array('product_id' => $product->id),
                        array('color_id' => $request->colors[$combination]),
                        // array('size_id' => $request->sizes[$combination]),
                        array('stock_depot' => $request->stocks_depot[$combination]),
                        array('stock_local' => $request->stocks_local[$combination]),
                        array('stock_truck' => $request->stocks_truck[$combination]),
                        $request->only('brand_id', 'category_id', 'code', 'gender', 'name')
                    );
        
                    $product_combination = $this->create($attributes);

                    if (isset($request->sizes[$combination])) {
                        $sizes = explode(',', $request->sizes[$combination]);
                        $product_sizes = array();

                        foreach ($sizes as $size) {
                            array_push(
                                $product_sizes,
                                array('size_id' => $size)
                            );
                        }

                        $product_combination->sizes()->createMany($product_sizes);
                    }
                }
            }

            // Update existing combinations
            /*
            if (isset($request->product_combinations) && count($request->product_combinations)) {
                foreach ($request->product_combinations as $product_combination) {
                    if ($product_combination = $this->find($product_combination)) {
                        $attributes = array_merge(
                            array('color_id' => $request->colors[$combination]),
                            array('stock_depot' => $request->stocks_depot[$combination]),
                            array('stock_local' => $request->stocks_local[$combination]),
                            array('stock_truck' => $request->stocks_truck[$combination]),
                            $request->only('brand_id', 'category_id', 'code', 'gender', 'name')
                        );
            
                        $this->update($product_combination, $attributes);

                        if (isset($request->sizes[$combination])) {
                            $sizes = explode(',', $request->sizes[$combination]);
                            $product_sizes = array();

                            foreach ($sizes as $size) {
                                array_push(
                                    $product_sizes,
                                    array('size_id' => $size)
                                );
                            }

                            $product_combination->sizes()->createMany($product_sizes);
                        }
                    }
                }
            }
            */
        } else if (isset($request->sizes)) {
            $sizes = explode(',', $request->sizes);
            $product_sizes = array();

            foreach ($sizes as $size) {
                array_push($product_sizes,
                    array('size_id' => $size)
                );
            }

            $product->sizes()->createMany($product_sizes);
        }
    }

    /**
    * @param $request
    * @return void
    */
    public function updateByRequest($id, $request): void
    {
        $attributes = $request->only('brand_id', 'category_id', 'code', 'gender', 'is_regular', 'name');

        if (isset($request->is_regular) && $request->is_regular) {
            $attributes = array_merge(
                $attributes,
                $request->only('color_id', 'size_id', 'stock_depot', 'stock_local', 'stock_truck')
            );
        }

        $product = $this->model->find($id);
        $product->update($attributes);

        if ($product->is_regular) {
            $product->product_combinations()->delete();

            if (isset($request->sizes)) {
                $product_sizes = array();
                $sizes = explode(',', $request->sizes);
                $product->sizes()->whereNotIn('size_id', $sizes)->delete(); # Delete all sizes not selected anymore
    
                # Fill array with news product sizes
                foreach ($sizes as $size) {
                    if (!$product->sizes()->where('size_id', $size)->first()) {
                        array_push($product_sizes,
                            array('size_id' => $size)
                        );
                    }
                }
    
                $product->sizes()->createMany($product_sizes);
            }
        } else if (!$product->is_regular) {
            // New combinations
            if (isset($request->combinations) && count($request->combinations)) {
                foreach ($request->combinations as $combination) {
                    $attributes = array_merge(
                        array('product_id' => $product->id),
                        array('color_id' => $request->colors[$combination]),
                        // array('size_id' => $request->sizes[$combination]),
                        array('stock_depot' => $request->stocks_depot[$combination]),
                        array('stock_local' => $request->stocks_local[$combination]),
                        array('stock_truck' => $request->stocks_truck[$combination]),
                        $request->only('brand_id', 'category_id', 'code', 'gender', 'name')
                    );
        
                    $product_combination = $this->create($attributes);

                    if (isset($request->sizes[$combination])) {
                        $sizes = explode(',', $request->sizes[$combination]);
                        $product_sizes = array();

                        foreach ($sizes as $size) {
                            array_push(
                                $product_sizes,
                                array('size_id' => $size)
                            );
                        }

                        $product_combination->sizes()->createMany($product_sizes);
                    }
                }
            }

            // Existing combinations
            if (isset($request->product_combinations) && is_array($request->product_combinations)) {
                foreach ($request->product_combinations as $product_combination_id) {
                    $product_combination = $product->product_combinations()->find($product_combination_id);

                    if ($product_combination) {
                        $attributes = array_merge(
                            array('color_id' => $request->colors_existing[$product_combination_id]),
                            array('stock_depot' => $request->stocks_depot_existing[$product_combination_id]),
                            array('stock_local' => $request->stocks_local_existing[$product_combination_id]),
                            array('stock_truck' => $request->stocks_truck_existing[$product_combination_id]),
                            $request->only('brand_id', 'category_id', 'code', 'gender', 'name')
                        );

                        $product_combination->update($attributes);

                        if (isset($request->sizes_existing[$product_combination->id])) {
                            $sizes = explode(',', $request->sizes_existing[$product_combination->id]);
                            $product_sizes = array();
                            $product_combination->sizes()->whereNotIn('size_id', $sizes)->delete(); # Delete all sizes not selected anymore
                
                            # Fill array with news product sizes
                            foreach ($sizes as $size) {
                                if (!$product_combination->sizes()->where('size_id', $size)->first()) {
                                    array_push($product_sizes,
                                        array('size_id' => $size)
                                    );
                                }
                            }
                
                            $product_combination->sizes()->createMany($product_sizes);
                        }
                    }
                }
            }
        }
    }
}