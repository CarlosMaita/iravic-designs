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
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $genders = GenderConstants::ALL;
        $sizes = Size::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();

        view()->share(compact('categories', 'brands', 'genders', 'sizes', 'colors'));
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
        $category = Category::where('slug', $slug)->firstOrFail();
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
