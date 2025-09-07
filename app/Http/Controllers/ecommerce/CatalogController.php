<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use App\Constants\GenderConstants;
use App\Helpers\ProductEcommerceHelper;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;

class CatalogController extends Controller
{
    /**
        * CatalogController constructor.
        *
        * Shares common data (categories, brands, genders, sizes, colors) with all views.
        * This data is used for displaying filters and other catalog-related information.
        *
        * @return void
        */
    public function __construct()
    {
        $categories = $this->getAvailableCategories();
        $brands = $this->getAvailableBrands();
        $genders = $this->getAvailableGenders();
        $sizes = $this->getAvailableSizes();
        $colors = $this->getAvailableColors();

        view()->share(compact('categories', 'brands', 'genders', 'sizes', 'colors'));
    }

    /**
     * Get categories that have products with stock
     */
    private function getAvailableCategories()
    {
        return Category::whereHas('products', function ($query) {
            $query->where('product_id', null) // Only main products
                  ->where(function ($q) {
                      // Check if product has stock through stores
                      $q->whereHas('stores', function ($storeQuery) {
                          $storeQuery->where('stock', '>', 0);
                      })
                      // OR products with stock through combinations
                      ->orWhereHas('product_combinations', function ($combinationQuery) {
                          $combinationQuery->whereHas('stores', function ($storeQuery) {
                              $storeQuery->where('stock', '>', 0);
                          });
                      });
                  });
        })->orderBy('name')->get();
    }

    /**
     * Get brands that have products with stock
     */
    private function getAvailableBrands()
    {
        return Brand::whereHas('products', function ($query) {
            $query->where('product_id', null) // Only main products
                  ->where(function ($q) {
                      // Check if product has stock through stores
                      $q->whereHas('stores', function ($storeQuery) {
                          $storeQuery->where('stock', '>', 0);
                      })
                      // OR products with stock through combinations
                      ->orWhereHas('product_combinations', function ($combinationQuery) {
                          $combinationQuery->whereHas('stores', function ($storeQuery) {
                              $storeQuery->where('stock', '>', 0);
                          });
                      });
                  });
        })->orderBy('name')->get();
    }

    /**
     * Get genders that have products with stock
     */
    private function getAvailableGenders()
    {
        $availableGenders = Product::where('product_id', null) // Only main products
            ->where(function ($q) {
                // Check if product has stock through stores
                $q->whereHas('stores', function ($storeQuery) {
                    $storeQuery->where('stock', '>', 0);
                })
                // OR products with stock through combinations
                ->orWhereHas('product_combinations', function ($combinationQuery) {
                    $combinationQuery->whereHas('stores', function ($storeQuery) {
                        $storeQuery->where('stock', '>', 0);
                    });
                });
            })
            ->whereNotNull('gender')
            ->distinct()
            ->pluck('gender')
            ->toArray();

        // Filter the GenderConstants::ALL array to only include available genders
        return array_intersect(GenderConstants::ALL, $availableGenders);
    }

    /**
     * Get sizes that have products with stock
     */
    private function getAvailableSizes()
    {
        return Size::whereHas('products', function ($query) {
            $query->whereHas('stores', function ($storeQuery) {
                $storeQuery->where('stock', '>', 0);
            });
        })->orderBy('name')->get();
    }

    /**
     * Get colors that have products with stock
     */
    private function getAvailableColors()
    {
        return Color::whereHas('products', function ($query) {
            $query->whereHas('stores', function ($storeQuery) {
                $storeQuery->where('stock', '>', 0);
            });
        })->orderBy('name')->get();
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $search = $this->getSearchInput();
        $category = request()->input('category', null);
        return view('ecommerce.catalog.index', compact('search', 'category'));
    }
    /**
        * Display the specified product.
        *
        * @param  string  $slug The slug of the product.
        * @return \Illuminate\View\View
        */

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $productDetail = (object)  ProductEcommerceHelper::getProductDetail($product);
        return view('ecommerce.product-detail.index' , compact('productDetail'));
    }

    /**
        * Display a listing of products for a specific category.
        *
        * @param  int  $slug ID of the category to display.
        * @return \Illuminate\View\View
        */
    public function category($slug)
    {
        $search = $this->getSearchInput();
        $category = Category::where('slug', $slug)->firstOrFail()->id;
        return view('ecommerce.catalog.index', compact('search','category'));
    }

    /**
     * Get the 'search' input from the request.
     *
     * @return mixed
     */
    private function getSearchInput()
    {
        return request()->input('search', null);
    }
}
