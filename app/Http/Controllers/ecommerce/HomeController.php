<?php

namespace App\Http\Controllers\ecommerce;

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

    /**
     * Display the application's home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('id')->get();
        $categories_cards = Category::withCount('products')->orderBy('id')->take(3)->get();
        $banners = \App\Models\Banner::orderBy('order')->get();
        $featuredProducts = Product::where('is_featured', true)
                                  ->with(['images', 'category'])
                                  ->orderBy('updated_at', 'desc')
                                  ->take(12)
                                  ->get();
        
        return view('ecommerce.home.index', compact('categories', 'categories_cards', 'banners', 'featuredProducts'));
    }

}

