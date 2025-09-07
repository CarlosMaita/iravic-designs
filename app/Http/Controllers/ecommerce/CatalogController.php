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
        // Temporary fix for missing stores table - return all categories
        return Category::whereHas('products', function ($query) {
            $query->where('product_id', null); // Only main products
        })->orderBy('name')->get();
    }

    /**
     * Get brands that have products with stock
     */
    private function getAvailableBrands()
    {
        // Temporary fix for missing stores table - return all brands
        return Brand::whereHas('products', function ($query) {
            $query->where('product_id', null); // Only main products
        })->orderBy('name')->get();
    }

    /**
     * Get genders that have products with stock
     */
    private function getAvailableGenders()
    {
        // Temporary fix for missing stores table - return all available genders
        $availableGenders = Product::where('product_id', null) // Only main products
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
        // Temporary fix for missing stores table - return all sizes
        return Size::whereHas('products')->orderBy('name')->get();
    }

    /**
     * Get colors that have products with stock
     */
    private function getAvailableColors()
    {
        // Temporary fix for missing stores table - return all colors
        return Color::whereHas('products')->orderBy('name')->get();
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
