<?php

namespace App\Http\Controllers;

use App\Constants\GenderConstants;
use App\Helpers\ProductEcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $genders = GenderConstants::ALL;
        $sizes = Size::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();

        view()->share(compact('categories', 'brands', 'genders', 'sizes', 'colors'));

    }

    public function index()
    {
        $search = request()->input('search', null);
        $category = request()->input('category', null);
        return view('ecommerce.catalog.index', compact('search', 'category'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $productDetail = (object)  ProductEcommerceHelper::getProductDetail($product);
        return view('ecommerce.product-detail.index' , compact('productDetail'));
    }

    public function category($id)
    {
        $search = request()->input('search', null);
        $category = Category::findOrFail($id)->id;
        return view('ecommerce.catalog.index', compact('search','category'));
    }

}

