<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ProductEcommerceHelper
{
    public static function getProductDetail($product)
    {

        $product->loadMissing([
            'product_combinations',
            'images',
            'category',
            'brand'
        ]);

        $productDetail = [
            'id' => $product->id,
            'name' => $product->name ?? '',
            'description' => $product->description ?? '',
            'is_regular' => $product->is_regular,
            'regular_price_str' => $product->regular_price_str,
            'images' => self::getImagesProduct($product->images),
        ];
      
        if ($product->product_combinations) {
            foreach ($product->product_combinations as $combination) {
                $index = array_search($combination->combination_index, array_column($productDetail['combinations'] ?? [], 'combination_index'));
                $combination_data = [
                    'id' => $combination->id,
                    'product_id' => $combination->product_id,
                    'size_id' => $combination->size_id,
                    'size_name' => $combination->size->name,
                    'price' => $combination->price,
                    'price_card_credit' => $combination->price_card_credit,
                    'price_credit' => $combination->price_credit,
                    'product_stores' => $product->stores->map(function ($store) use ($combination) {
                        return [
                            'store_id' => $store->id,
                            'store_name' => $store->name,
                            'stock' => optional($combination->stores->firstWhere('id', $store->id))->pivot->stock ?? 0,
                        ];
                    })
                ];

                if ($index === false) {
                    $productDetail['combinations'][] = [
                        'id' => $combination->id,
                        'color_id' => $combination->color_id,
                        'text_color' => $combination->text_color ? $combination->text_color : $combination->color->name,
                        'url_thumbnail' => self::getUrlThumbnailCombination($combination->combination_index, $product->images),
                        'images' =>  self::getImagesCombination($combination->combination_index, $product->images),
                        'combination_index' => $combination->combination_index,
                        'color' => $combination->color,
                        'sizes' => [$combination_data]
                    ];
                } else {
                    $productDetail['combinations'][$index]['sizes'][] = $combination_data;
                }
            }
        }

        // 'image_url' => self::getUrlImageCombination($combination->combination_index, $product->images)
        

        return $productDetail;
    }  
    
    private static function getUrlThumbnailCombination($combination_index, $images)
    {
        $image = $images->where('combination_index', $combination_index)->first();
        return $image?->url_img;
    }

    private static function getImagesCombination($combination_index, $images): array
    {
        $images = $images->where('combination_index', $combination_index)
                      ->map(fn($image) =>  $image->url_img)
                      ->toArray();

        return [...$images];
    }

     private static function getImagesProduct($images): array
    {
        $images = $images->map(fn($image) =>  $image->url_img)
                    ->toArray();

        return [...$images];
    }
}