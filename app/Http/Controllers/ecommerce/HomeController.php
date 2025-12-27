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
                                      ->with(['images' => function($query) {
                                          $query->orderByDesc('is_primary')->orderBy('position');
                                      }, 'category'])
                                      ->orderBy('updated_at', 'desc')
                                      ->take(12)
                                      ->get();
            
            // If no featured products found, use sample data
            if ($featuredProducts->isEmpty()) {
                throw new \Exception('No featured products found, using sample data');
            }
        } catch (\Exception $e) {
            // Create sample featured products for demo when database is not fully set up
            $featuredProducts = collect([
                (object)[
                    'id' => 1,
                    'name' => 'Vestido Elegante Niña',
                    'slug' => 'vestido-elegante-nina',
                    'price' => 45.99,
                    'original_price' => 59.99,
                    'has_discount' => true,
                    'discount_percentage' => 23,
                    'rating' => 4.5,
                    'reviews_count' => 12,
                    'images' => collect([
                        (object)['full_url_img' => 'https://via.placeholder.com/400x400/e3f2fd/1976d2?text=Vestido+Elegante']
                    ])
                ],
                (object)[
                    'id' => 2,
                    'name' => 'Conjunto Deportivo Niño',
                    'slug' => 'conjunto-deportivo-nino',
                    'price' => 32.50,
                    'original_price' => null,
                    'has_discount' => false,
                    'rating' => 4.8,
                    'reviews_count' => 8,
                    'images' => collect([
                        (object)['full_url_img' => 'https://via.placeholder.com/400x400/f3e5f5/7b1fa2?text=Conjunto+Deportivo']
                    ])
                ],
                (object)[
                    'id' => 3,
                    'name' => 'Blusa Casual Niña',
                    'slug' => 'blusa-casual-nina',
                    'price' => 28.99,
                    'original_price' => 35.99,
                    'has_discount' => true,
                    'discount_percentage' => 19,
                    'rating' => 4.2,
                    'reviews_count' => 15,
                    'images' => collect([
                        (object)['full_url_img' => 'https://via.placeholder.com/400x400/e8f5e8/388e3c?text=Blusa+Casual']
                    ])
                ],
                (object)[
                    'id' => 4,
                    'name' => 'Pantalón Vaquero Niño',
                    'slug' => 'pantalon-vaquero-nino',
                    'price' => 38.75,
                    'original_price' => null,
                    'has_discount' => false,
                    'rating' => 4.6,
                    'reviews_count' => 6,
                    'images' => collect([
                        (object)['full_url_img' => 'https://via.placeholder.com/400x400/fff3e0/f57c00?text=Pantalón+Vaquero']
                    ])
                ],
                (object)[
                    'id' => 5,
                    'name' => 'Vestido Princesa Niña',
                    'slug' => 'vestido-princesa-nina',
                    'price' => 52.99,
                    'original_price' => 69.99,
                    'has_discount' => true,
                    'discount_percentage' => 24,
                    'rating' => 4.9,
                    'reviews_count' => 22,
                    'images' => collect([
                        (object)['full_url_img' => 'https://via.placeholder.com/400x400/fce4ec/c2185b?text=Vestido+Princesa']
                    ])
                ],
                (object)[
                    'id' => 6,
                    'name' => 'Camisa Formal Niño',
                    'slug' => 'camisa-formal-nino',
                    'price' => 29.99,
                    'original_price' => null,
                    'has_discount' => false,
                    'rating' => 4.3,
                    'reviews_count' => 9,
                    'images' => collect([
                        (object)['full_url_img' => 'https://via.placeholder.com/400x400/e1f5fe/0277bd?text=Camisa+Formal']
                    ])
                ]
            ]);
        }
        
        // Handle special offers
          try {
                $specialOffers = SpecialOffer::active()
                                                    ->current()
                                                    ->with(['product', 'product.images'])
                                                    ->ordered()
                                                    ->take(12)
                                                    ->get()
                                                    ->filter(function($offer){
                                                        // Seguridad extra: no mostrar si ya venció al día de hoy
                                                        return $offer->end_date && $offer->end_date->isFuture();
                                                    })
                                                    ->take(6)
                                                    ->values();
        } catch (\Exception $e) {
            $specialOffers = collect(); // Empty collection if table doesn't exist
        }
        
        return view('ecommerce.home.index', compact('categories', 'categories_cards', 'banners', 'featuredProducts', 'specialOffers'));
    }

}

