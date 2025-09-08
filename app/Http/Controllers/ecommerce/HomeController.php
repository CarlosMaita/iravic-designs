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
use App\Models\SpecialOffer;
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
        // For demo purposes, use empty collections if tables don't exist
        try {
            $categories = Category::withCount('products')->orderBy('id')->get();
            $categories_cards = Category::withCount('products')->orderBy('id')->take(3)->get();
        } catch (\Exception $e) {
            $categories = collect();
            $categories_cards = collect();
        }
        
        // Handle case where banners table doesn't exist yet
        try {
            $banners = \App\Models\Banner::orderBy('order')->get();
        } catch (\Exception $e) {
            $banners = collect(); // Empty collection if table doesn't exist
        }
        
        // Handle case where is_featured column doesn't exist yet
        try {
            $featuredProducts = Product::where('is_featured', true)
                                      ->with(['images', 'category'])
                                      ->orderBy('updated_at', 'desc')
                                      ->take(12)
                                      ->get();
        } catch (\Exception $e) {
            $featuredProducts = collect(); // Empty collection if column doesn't exist
        }
        
        // Handle special offers
        try {
            $specialOffers = SpecialOffer::active()
                                       ->current()
                                       ->with(['product', 'product.images'])
                                       ->ordered()
                                       ->take(6)
                                       ->get();
        } catch (\Exception $e) {
            $specialOffers = collect(); // Empty collection if table doesn't exist
        }
        
        return view('ecommerce.home.index', compact('categories', 'categories_cards', 'banners', 'featuredProducts', 'specialOffers'));
    }

}

