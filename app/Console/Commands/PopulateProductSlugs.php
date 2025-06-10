<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Str;

class PopulateProductSlugs extends Command
{
    protected $signature = 'products:populate-slugs';
    protected $description = 'Populate slug field for existing products';

    public function handle()
    {
        $this->info('Populating slugs for products...');
        $count = 0;
        foreach (Product::whereNull('slug')->orWhere('slug', '')->get() as $product) {
            $slug = Str::slug($product->name);
            // Ensure uniqueness
            $originalSlug = $slug;
            $i = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $i;
                $i++;
            }
            $product->slug = $slug;
            $product->save();
            $count++;
        }
        $this->info("Slugs populated for {$count} products.");
    }
}
