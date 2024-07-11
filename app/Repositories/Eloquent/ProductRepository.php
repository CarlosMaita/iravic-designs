<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use App\Services\Images\ImageService;
use Illuminate\Support\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    private $filedisk = 'products';

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

    public function onlyPrincipalsQuery($criteria = null)
    {
        $query = $this->model->doesntHave('product_parent')->with(['brand', 'category']);
        
        if (isset($criteria['brand']) && is_array($criteria['brand'])) {
            $query->whereInBrand($criteria['brand']);
        }

        if (isset($criteria['category']) && is_array($criteria['category'])) {
            $query->whereInCategory($criteria['category']);
        }

        if (isset($criteria['gender']) && is_array($criteria['gender'])) {
            $query->whereInGender($criteria['gender']);
        }

        if (isset($criteria['color']) && is_array($criteria['color'])) {
            $query->whereInColor($criteria['color']);
        }

        if (isset($criteria['size']) && is_array($criteria['size'])) {
            $query->whereInSize($criteria['size']);
        }

        if (!empty($criteria['price_from'])) {
            $query->wherePrice($criteria['price_from'], '>=');
        }

        if (!empty($criteria['price_to'])) {
            $query->wherePrice($criteria['price_to'], '<=');
        }

        return $query->orderBy('name');
    }

    /**
     * Retorna listado de productos principales. 
     * Producto principal = Producto que no tenga combinacion o producto general de un grupo de combinaciones
     * Se puede filtrar por marca, categoria, genero, color, talla, rango de precio
     * 
     * @return Collection
     */
    public function onlyPrincipals($criteria = null): Collection
    {
        $query = $this->model->doesntHave('product_parent')->with(['brand', 'category']);
        
        if (isset($criteria['brand']) && is_array($criteria['brand'])) {
            $query->whereInBrand($criteria['brand']);
        }

        if (isset($criteria['category']) && is_array($criteria['category'])) {
            $query->whereInCategory($criteria['category']);
        }

        if (isset($criteria['gender']) && is_array($criteria['gender'])) {
            $query->whereInGender($criteria['gender']);
        }

        if (isset($criteria['color']) && is_array($criteria['color'])) {
            $query->whereInColor($criteria['color']);
        }

        if (isset($criteria['size']) && is_array($criteria['size'])) {
            $query->whereInSize($criteria['size']);
        }

        if (!empty($criteria['price_from'])) {
            $query->wherePrice($criteria['price_from'], '>=');
        }

        if (!empty($criteria['price_to'])) {
            $query->wherePrice($criteria['price_to'], '<=');
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Crea un producto con sus combinaciones si tiene. Se valida si tiene imagen para mandar a guardar
     * 
    * @param $request
    * @return void
    */
    public function createByRequest($request): void
    {
        $attributes = $request->only('brand_id', 'category_id', 'code', 'gender', 'is_regular', 'name', 
        'price');

        if (isset($request->is_regular) && $request->is_regular) {
            $attributes = array_merge(
                $attributes,
                $request->only('stock_depot', 'stock_local', 'stock_truck')
            );
        }

        $product = $this->create($attributes);

        // Create new combinations
        if ((!isset($request->is_regular) || !$request->is_regular ) && isset($request->combinations_group)) {
            foreach(array_keys($request->combinations_group) as $key) {
                if (isset($request->combinations[$key])) {
                    foreach (array_keys($request->combinations[$key]) as $key_new_combination) {
                        $attributes = array_merge(
                            array(
                                'product_id' => $product->id,
                                'code' => $request->codes[$key][$key_new_combination],
                                'color_id' => $request->colors[$key][$key_new_combination],
                                'size_id' => $request->sizes[$key][$key_new_combination],
                                'price' => $request->prices[$key][$key_new_combination],
                                'stock_depot' => $request->stocks_depot[$key][$key_new_combination],
                                'stock_local' => $request->stocks_local[$key][$key_new_combination],
                                'stock_truck' => $request->stocks_truck[$key][$key_new_combination]
                            ),
                            $request->only('brand_id', 'category_id', 'gender', 'name')
                        );
                        
                        $this->create($attributes);
                    }
                }
            }
        }

        // Images
        if (isset($request->file)) {
            $this->saveImages($product, $request->file);
        }
    }

    /**
     * Actualiza un producto y sus combinaciones si tiene o tenia. Se valida si tiene imagen para mandar a guardar
     * 
    * @param $request
    * @return void
    */
    public function updateByRequest($id, $request): void
    {
        $attributes = $request->only('brand_id', 'category_id', 'code', 'gender', 'is_regular', 'name', 
        'price');

        if (isset($request->is_regular) && $request->is_regular) {
            $attributes = array_merge(
                $attributes,
                $request->only('stock_depot', 'stock_local', 'stock_truck')
            );
        }

        $product = $this->model->find($id);
        $category_id_old = $product->category_id;
        $gender_old = $product->gender;
        
        // Update product 
        $product->update($attributes);

        if ($product->is_regular) {
            $product->product_combinations()->delete();
        }else if (!$product->is_regular && isset($request->combinations_group)) {
            // Delete combinations
            if($category_id_old != $request->category_id || $gender_old != $request->gender) {
                $product->product_combinations()->delete();
            }
            foreach(array_keys($request->combinations_group) as $key) {
                if (isset($request->product_combinations[$key])) {
                    foreach ($request->product_combinations[$key] as $product_combination_id) {
                        $product_combination = $product->product_combinations()->find($product_combination_id);

                        if ($product_combination) {
                            $attributes = array_merge(
                                array(
                                    'code' => $request->codes_existing[$key][$product_combination_id],
                                    'color_id' => $request->colors_existing[$key][$product_combination_id],
                                    'size_id' => $request->sizes_existing[$key][$product_combination_id],
                                    'price' => $request->prices_existing[$key][$product_combination_id],
                                    'stock_depot' => $request->stocks_depot_existing[$key][$product_combination_id],
                                    'stock_local' => $request->stocks_local_existing[$key][$product_combination_id],
                                    'stock_truck' => $request->stocks_truck_existing[$key][$product_combination_id]
                                ),
                                $request->only('brand_id', 'category_id', 'gender', 'name')
                            );

                            $this->update($product_combination->id, $attributes);
                        }
                    }
                }
                
                if (isset($request->combinations[$key])) {
                    $total_existing = isset($request->product_combinations[$key]) && is_array($request->product_combinations[$key]) 
                                        ? count($request->product_combinations[$key]) 
                                        : 0;
                    foreach (array_keys($request->combinations[$key]) as $key_new_combination) {
                        $attributes = array_merge(
                            array(
                                'product_id' => $product->id,
                                'code' => $request->codes[$key][($key_new_combination + $total_existing)],
                                'color_id' => $request->colors[$key][($key_new_combination + $total_existing)],
                                'size_id' => $request->sizes[$key][($key_new_combination + $total_existing)],
                                'price' => $request->prices[$key][($key_new_combination + $total_existing)],
                                'stock_depot' => $request->stocks_depot[$key][($key_new_combination + $total_existing)],
                                'stock_local' => $request->stocks_local[$key][($key_new_combination + $total_existing)],
                                'stock_truck' => $request->stocks_truck[$key][($key_new_combination + $total_existing)]
                            ),
                            $request->only('brand_id', 'category_id', 'gender', 'name')
                        );

                        $this->create($attributes);
                    }
                }
            }
        }

        // Images
        if (isset($request->file)) {
            $this->saveImages($product, $request->file);
        }
    }

    /**
     * Elimina grupo de productos por sus ids
     * 
    * @param $ids
    * @return void
    */
    public function deleteByIds($ids): bool
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * Almacena imagenes de un producto llamando al servicio de Imagenes
     * 
    * @param product
    * @param files
    * @return void
    */
    public function saveImages($product, $files): void
    {
        $filesname = array();

        foreach ($files as $file) {
            $url = ImageService::save($this->filedisk, $file);

            if ($url) {
                array_push($filesname, array('url' => $url));
            }
        }

        $product->images()->createMany($filesname);
    }
}