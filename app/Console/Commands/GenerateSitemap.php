<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Remove existing sitemap.xml file
        if (file_exists(public_path('sitemap.xml'))) {
            unlink(public_path('sitemap.xml'));
        }

        $sitemap = SitemapGenerator::create(config('app.url'))
            ->getSitemap();
       

        // Add static pages
        $sitemap->add(Url::create('/catalogo')->setPriority(0.8));

        // Add categories
        Category::all()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(Url::create("/categoria/{$category->id}")->setPriority(0.7));
        });

        // Add products
        Product::all()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(Url::create("/producto/{$product->slug}")->setPriority(0.6));
        });


        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');

        return 0;
    }
}
