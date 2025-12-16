<?php

namespace App\Models;

use App\Services\Images\ImageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    public $table = 'products_images';

    public $fillable = [
        'product_id',
        'url',
        'color_id' ,
        'combination_index',
        'temp_code',
        'url_original',
        'is_primary'
    ];

    public $appends = [
        'url_img',
        'full_url_img'
    ];

    private $filedisk = 'products';

    # Boot
    protected static function boot()
    {
        parent::boot();

        # Se elimina la imagen del disco (Storage) cuando se elimina su registro de la BD
        ProductImage::deleting(function ($model) {
            ImageService::delete($model->filedisk, $model->url);
        });

        # Ensure only one image is primary per product
        ProductImage::saving(function ($model) {
            if ($model->is_primary && $model->product_id) {
                // Unset is_primary for all other images of the same product
                ProductImage::where('product_id', $model->product_id)
                    ->where('id', '!=', $model->id)
                    ->update(['is_primary' => false]);
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }   

    # Accessor
    public function getUrlImgAttribute()
    {
        if (Storage::disk($this->filedisk)->exists($this->url)) {
            return Storage::disk($this->filedisk)->url($this->url);
        }

        return url("/img/no_image.jpg");
    }
    public function getFullUrlImgAttribute()
    {
        return asset( $this->url_img );
    }
}
