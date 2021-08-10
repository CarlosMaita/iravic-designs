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
        'url'
    ];

    public $appends = [
        'url_img'
    ];

    private $filedisk = 'products';

    # Boot
    protected static function boot()
    {
        parent::boot();
        ProductImage::deleting(function ($model) {
            ImageService::delete($model->filedisk, $model->url);
        });
    }

    # Accessor
    public function getUrlImgAttribute()
    {
        if (Storage::disk($this->filedisk)->exists($this->url)) {
            return Storage::disk($this->filedisk)->url($this->url);
        }

        return url("/img/no_image.jpg");
    }
}
