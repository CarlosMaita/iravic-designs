<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         $this->loadMissing([
            'product_combinations',
            'images',
            'category',
            'brand'
        ]);
        
        $product = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_regular' => $this->is_regular,
            'regular_price' => $this->regular_price,
            'regular_price_str' => $this->regular_price_str,
            'url_detail' => $this->slug ? route('ecommerce.product.detail', $this->slug) : '#',
            'url_thumbnail' => self::getUrlThumbnailProduct($this->images),
        ];

      
        if ($this->product_combinations) {
            foreach ($this->product_combinations as $combination) {
                $index = array_search($combination->combination_index, array_column($product['combinations'] ?? [], 'combination_index'));
                $combination_data = [
                    'id' => $combination->id,
                    'product_id' => $combination->product_id,
                    'size_id' => $combination->size_id,
                    'size_name' => $combination->size->name,
                    'price' => $combination->price,
                    'product_stores' => $this->stores->map(function ($store) use ($combination) {
                        return [
                            'store_id' => $store->id,
                            'store_name' => $store->name,
                            'stock' => optional($combination->stores->firstWhere('id', $store->id))->pivot->stock ?? 0,
                        ];
                    })
                ];

                if ($index === false) {
                    $product['combinations'][] = [
                        'id' => $combination->id,
                        'color_id' => $combination->color_id,
                        'color_code' => $combination->color->code,
                        'text_color' => $combination->text_color ? $combination->text_color : $combination->color->name,
                        'url_thumbnail' => self::getUrlThumbnailCombination($combination->combination_index, $this->images),
                        'images' => self::getImagesCombination($combination->combination_index, $this->images),
                        'combination_index' => $combination->combination_index,
                        'color' => $combination->color,
                        'sizes' => [$combination_data]
                    ];
                } else {
                    $product['combinations'][$index]['sizes'][] = $combination_data;
                }
            }
        }
        return $product;
    }

     private static function getUrlThumbnailCombination($combination_index, $images)
    {
        // First try to get the primary image for this combination
        $primaryImage = $images->where('combination_index', $combination_index)
                              ->where('is_primary', true)
                              ->first();
        
        if ($primaryImage) {
            return $primaryImage->url_img;
        }
        
        // Fallback to the first image of the combination
        $image = $images->where('combination_index', $combination_index)->first();
        return $image?->url_img;
    }

    private static function getUrlThumbnailProduct($images)
    {
        // First try to get the primary image
        $primaryImage = $images->where('is_primary', true)->first();
        
        if ($primaryImage) {
            return $primaryImage->url_img;
        }
        
        // Fallback to the first image
        $image = $images->first();
        return $image?->url_img;
    }

    private static function getImagesCombination($combination_index, $images): array
    {
        return $images->where('combination_index', $combination_index)
            ->sortByDesc('is_primary') // Primary image first
            ->map(function ($image) {
                return [
                    'url' => $image->url_img,
                ];
            })->toArray();
    }
}
