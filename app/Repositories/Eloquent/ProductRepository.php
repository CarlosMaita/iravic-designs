<?php

namespace App\Repositories\Eloquent;

use App\Events\ProductStockChanged;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use App\Repositories\ProductRepositoryInterface;
use App\Services\Images\ImageService;
use GuzzleHttp\Promise\Create;
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
        $query = $this->model->doesntHave('product_parent')->with(['brand', 'category', 'stores', 'product_combinations.stores']);

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
        if ($request->has('is_regular') && $request->is_regular) {
            $this->createRegularProduct( $request);
        } else {
            $this->createNonRegularProduct( $request);
        }
    }

    /**
     * Crea un producto regular
     * 
     * @param $request
     * @return void
     */
    private function createRegularProduct( $request): void
    {
        $attributes = $request->only(
            'brand_id',
            'category_id',
            'code',
            'gender',
            'is_regular',
            'name',
            'description',
            'price',
            'price_card_credit',
            'price_credit',
            'stock_depot',
            'stock_local',
            'stock_truck',
        );

        $product = $this->create($attributes);
        
        //attach stores 
        foreach($request->stores as $store_id => $stock){
            $product->stores()->attach($store_id, ['stock' => $stock]);
            // Evento de cambio de stock
            event(new ProductStockChanged($product->id, $store_id, 0, $stock, $stock, 'Asignacion inicial de stock' , auth()->id()));
        }
        
        // atach images if exists
        $files = $request->file('file', []);
        if (!empty($files)) {
            $this->saveImages($product, $files);
        }
       
    }

    /**
     * Crea un producto NO regular
     * 
     * @param $request
     * @return void
     */
    private function createNonRegularProduct($request): void
    {
        $attributes = $request->only(
            'brand_id',
            'category_id',
            'code',
            'gender',
            'is_regular',
            'name',
            'description',
            'price',
            'price_card_credit',
            'price_credit'
        );

        $product = $this->create($attributes);

        // Create new combinations
        if ((!isset($request->is_regular) || !$request->is_regular) && isset($request->combinations_group)) {
            foreach (array_keys($request->combinations_group) as $key) {
                if (isset($request->combinations[$key])) {
                    foreach (array_keys($request->combinations[$key]) as $key_new_combination) {
                        $attributes = array_merge(
                            array(
                                'product_id' => $product->id,
                                'combination_index' => $key,
                                'code' => $request->codes[$key][$key_new_combination],
                                'color_id' => $request->colors[$key][$key_new_combination],
                                'text_color' => $request->text_colors[$key][$key_new_combination],
                                'size_id' => $request->sizes[$key][$key_new_combination],
                                'price' => $request->prices[$key][$key_new_combination],
                                'price_card_credit' => $request->prices_card_credit[$key][$key_new_combination],
                                'price_credit' => $request->prices_credit[$key][$key_new_combination],
                                'stock_depot' => $request->stocks_depot[$key][$key_new_combination],
                                'stock_local' => $request->stocks_local[$key][$key_new_combination],
                                'stock_truck' => $request->stocks_truck[$key][$key_new_combination]
                            ),
                            $request->only('brand_id', 'category_id', 'gender', 'name')
                        );
                        
                        $product_combination = $this->create($attributes);

                        //attach stores 
                        $stores = Store::all();
                        foreach($stores as $store){
                            $input_store = $request->input("store_".$store->id);
                            $stock = $input_store[$key][$key_new_combination];
                            $product_combination->stores()->attach($store->id, ['stock' => $stock]);   
                            // Evento de cambio de stock
                            event(new ProductStockChanged($product_combination->id, $store->id, 0, $stock, $stock, 'Asignacion inicial de stock' , auth()->id()));
                        }
                        
                    }

                    // attach images if exists
                    if (isset($request->temp_code)) {
                        $productImages = ProductImage::where('temp_code', $request->temp_code)
                            ->where('combination_index', $key)
                            ->get();
                        ProductImage::where('temp_code', $request->temp_code)
                            ->where('combination_index', $key)
                            ->update([
                                'product_id' => $product->id,
                                'color_id' => $request->colors[$key][0],
                                'temp_code' => null
                            ]);
                    }

                }
            }
        }
        // Clean Storage Images
        $this->cleanStorageImages();
    }

    
    private function cleanStorageImages(): void
    {
        ProductImage::where('temp_code', '!=' , null)->delete();
    }

    /**
     * Actualiza un producto y sus combinaciones si tiene o tenia. Se valida si tiene imagen para mandar a guardar
     * 
    * @param $request
    * @return void
    */
    public function updateByRequest($id, $request): void
    {
        if ($request->has('is_regular') && $request->is_regular) {
            $this->updateRegularProduct($id, $request);
        } else {
            $this->updateNonRegularProduct($id, $request);
        }
    }

    private function updateRegularProduct($id, $request): void
    {
        $attributes = $request->only(
            'brand_id',
            'category_id',
            'code',
            'gender',
            'is_regular',
            'name',
            'description',
            'price',
            'price_card_credit',
            'price_credit',
            'stock_depot',
            'stock_local',
            'stock_truck'
        );

        $product = $this->model->find($id);
        $product->update($attributes);
        $product->product_combinations()->delete();

        // old_stock
        $old_store = $product->store();
        //attach stores 
        $product->stores()->detach();
        $stores = $request->input('stores');
        foreach ($stores as $store_id => $stock) {
            $product->stores()->attach($store_id, ['stock' => $stock]);
            $old_stock = $old_store->find($store_id)->pivot->stock;
            // Evento de Actualizacion de stock
            event(new ProductStockChanged($product->id, $store_id, $old_stock, $stock, ($stock - $old_stock), 'Actualizacion de stock' , auth()->id()));
        }
        

        // atach images if exists
        $files = $request->file('file', []);
        if (!empty($files)) {
            $this->saveImages($product, $files);
        }
    }

    private function updateNonRegularProduct($id, $request): void
    {
        $attributes = $request->only(
            'brand_id',
            'category_id',
            'code',
            'gender',
            'is_regular',
            'name',
            'description',
            'price',
            'price_card_credit',
            'price_credit',
        );
        // Reset stock
        $attributes['stock_depot'] = 0;
        $attributes['stock_local'] = 0;
        $attributes['stock_truck'] = 0;

        $product = $this->model->find($id);
        $category_id_old = $product->category_id;
        $gender_old = $product->gender;
        
        // Update product 
        $product->update($attributes);
        
        if (isset($request->combinations_group)){
            // Delete combinations
            if ($category_id_old != $request->category_id || $gender_old != $request->gender) {
                $product->product_combinations()->delete();
                $product->images()->delete();
            }
            foreach (array_keys($request->combinations_group) as $key) {
                if (isset($request->product_combinations[$key])) {
                    foreach ($request->product_combinations[$key] as $product_combination_id) {
                        $product_combination = $product->product_combinations()->find($product_combination_id);
                        $old_combination_index = $product_combination->combination_index;

                        if ($product_combination) {
                            $attributes = array_merge(
                                array(
                                    'combination_index' => $key,
                                    'code' => $request->codes_existing[$key][$product_combination_id],
                                    'color_id' => $request->colors_existing[$key][$product_combination_id],
                                    'text_color' => $request->text_colors_existing[$key][$product_combination_id],
                                    'size_id' => $request->sizes_existing[$key][$product_combination_id],
                                    'price' => $request->prices_existing[$key][$product_combination_id],
                                    'price_card_credit' => $request->prices_card_credit_existing[$key][$product_combination_id],
                                    'price_credit' => $request->prices_credit_existing[$key][$product_combination_id],
                                    'stock_depot' => $request->stocks_depot_existing[$key][$product_combination_id],
                                    'stock_local' => $request->stocks_local_existing[$key][$product_combination_id],
                                    'stock_truck' => $request->stocks_truck_existing[$key][$product_combination_id]
                                ),
                                $request->only('brand_id', 'category_id', 'gender', 'name')
                            );


                           $this->update($product_combination->id, $attributes);
                            
                            // Attach stores with updated stock
                            $allStores = Store::all();
                            foreach ($allStores as $store) {
                                $storeStockInput = $request->input("store_{$store->id}_existing");
                                $storeStock = $storeStockInput[$key][$product_combination_id];
                                // Verificar si el vínculo ya existe
                                if ($product_combination->stores()->where('store_id', $store->id)->exists()) {
                                    $old_stock = $product_combination->stores()->find($store->id)->pivot->stock;
                                    // Actualizar el stock existente
                                    $product_combination->stores()->updateExistingPivot($store->id, ['stock' => $storeStock]);
                                    // Evento de Actualizacion de stock
                                    event(new ProductStockChanged($product_combination->id, $store->id, $old_stock, $storeStock, ($storeStock - $old_stock), 'Actualización de stock' , auth()->id()));
                                } else {
                                    // Crear un nuevo vínculo si no existe
                                    $product_combination->stores()->attach($store->id, ['stock' => $storeStock]);
                                    // Evento de Asignacion inicial de stock
                                    event(new ProductStockChanged($product_combination->id, $store->id, 0, $storeStock, ($storeStock - 0), 'Asignación inicial de stock' , auth()->id()));
                                }
                            }
                        }

                        // update images if exists - combination_index
                        ProductImage::where('product_id', $product->id)
                            ->where('combination_index', $old_combination_index)
                            ->update([
                                'combination_index' => $key
                            ]);
                    }


                    // attach images if exists
                    if (isset($request->temp_code)) {
                    $productImages = ProductImage::where('temp_code', $request->temp_code)
                        ->where('combination_index', $key)
                        ->get();
                    ProductImage::where('temp_code', $request->temp_code)
                    ->where('combination_index', $key)
                    ->update([
                        'product_id' => $product->id,
                        'color_id' =>  $request->colors_existing[$key][$request->product_combinations[$key][0] ],
                        'temp_code' => null
                    ]);
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
                                'combination_index' => $key,
                                'code' => $request->codes[$key][($key_new_combination + $total_existing)],
                                'color_id' => $request->colors[$key][($key_new_combination + $total_existing)],
                                'text_color' => $request->text_colors[$key][($key_new_combination + $total_existing)],
                                'size_id' => $request->sizes[$key][($key_new_combination + $total_existing)],
                                'price' => $request->prices[$key][($key_new_combination + $total_existing)],
                                'price_card_credit' => $request->prices_card_credit[$key][($key_new_combination + $total_existing)],
                                'price_credit' => $request->prices_credit[$key][($key_new_combination + $total_existing)],
                                'stock_depot' => $request->stocks_depot[$key][($key_new_combination + $total_existing)],
                                'stock_local' => $request->stocks_local[$key][($key_new_combination + $total_existing)],
                                'stock_truck' => $request->stocks_truck[$key][($key_new_combination + $total_existing)]
                            ),
                            $request->only('brand_id', 'category_id', 'gender', 'name')
                        );

                        $product_combination = $this->create($attributes);

                         // Attach stores with updated stock
                         $allStores = Store::all();
                         foreach ($allStores as $store) {
                             $storeStockInput = $request->input("store_".$store->id);
                             $storeStock = $storeStockInput[$key][($key_new_combination + $total_existing)];
                             // Verificar si el vínculo ya existe
                             if ($product_combination->stores()->where('store_id', $store->id)->exists()) {
                                $old_stock = $product_combination->stores()->find($store->id)->pivot->stock;
                                // Actualizar el stock existente
                                $product_combination->stores()->updateExistingPivot($store->id, ['stock' => $storeStock]);
                                // Evento de Actualizacion de stock
                                event(new ProductStockChanged($product_combination->id, $store->id, $old_stock, $storeStock, ($storeStock - $old_stock), 'Actualización de stock' , auth()->id()));
                             } else {
                                 // Crear un nuevo vínculo si no existe
                                 $product_combination->stores()->attach($store->id, ['stock' => $storeStock]);
                                 // Evento de Asignacion inicial de stock
                                 event(new ProductStockChanged($product_combination->id, $store->id, 0, $storeStock, ($storeStock - 0), 'Asignación inicial de stock' , auth()->id()));
                             }
                         }
                    }

                     // attach images if exists
                     if (isset($request->temp_code)) {
                        $productImages = ProductImage::where('temp_code', $request->temp_code)
                            ->where('combination_index', $key)
                            ->get();
                        ProductImage::where('temp_code', $request->temp_code)
                            ->where('combination_index', $key)
                            ->update([
                                'product_id' => $product->id,
                                'color_id' => $request->colors[$key][($key_new_combination + $total_existing)],
                                'temp_code' => null
                            ]);
                    }

                }
            }
        }

        // Clean Storage Images
        $this->cleanStorageImages();
    }

    public function updateStoreStock($id, $request): void
    {
        $product = $this->model->find($id);
        if ($product) {
            $store_id = $request->stock_id;
            $storeStock = $request->stock;
            $old_stock = $product->stores()->find( $store_id)->pivot->stock;
            $product->stores()->updateExistingPivot($store_id, ['stock' => $storeStock]);
             // Evento de Actualizacion de stock
             event(new ProductStockChanged($product->id, $store_id, $old_stock, $storeStock, ($storeStock - $old_stock), 'Actualización de stock' , auth()->id()));
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
        $products = $this->model->with('product_parent', 'product_parent.images')->whereIn('id', $ids)->get();
        foreach ($products as $product) {
            $product->product_parent
                ->images()
                ->where('combination_index', $product->combination_index)
                ->delete();
        }

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