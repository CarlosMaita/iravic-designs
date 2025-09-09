<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    /**
     * Generate and return the sitemap XML
     */
    public function index()
    {
        $sitemap = Sitemap::create();

        // Add static pages
        $this->addStaticPages($sitemap);
        
        // Add category pages
        $this->addCategoryPages($sitemap);
        
        // Add product detail pages
        $this->addProductPages($sitemap);

        return $sitemap->toResponse(request());
    }

    /**
     * Add static pages to the sitemap
     */
    private function addStaticPages(Sitemap $sitemap): void
    {
        // Home page
        $sitemap->add(
            Url::create(route('ecommerce.home'))
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        // Catalog page
        $sitemap->add(
            Url::create(route('ecommerce.catalog'))
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );

        // Login page
        $sitemap->add(
            Url::create(route('customer.login.form'))
                ->setLastModificationDate(Carbon::now()->subWeek())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.3)
        );

        // Register page
        $sitemap->add(
            Url::create(route('customer.register.form'))
                ->setLastModificationDate(Carbon::now()->subWeek())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.3)
        );
    }

    /**
     * Add category pages to the sitemap
     */
    private function addCategoryPages(Sitemap $sitemap): void
    {
        $categories = Category::whereHas('products', function ($query) {
            $query->where('product_id', null); // Only main products
        })->orderBy('updated_at', 'desc')->get();

        foreach ($categories as $category) {
            $sitemap->add(
                Url::create(route('ecommerce.categoria', $category->slug))
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        }
    }

    /**
     * Add product detail pages to the sitemap
     */
    private function addProductPages(Sitemap $sitemap): void
    {
        $products = Product::where('product_id', null) // Only main products
            ->whereNotNull('slug')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($products as $product) {
            $sitemap->add(
                Url::create(route('ecommerce.product.detail', $product->slug))
                    ->setLastModificationDate($product->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7)
            );
        }
    }
}