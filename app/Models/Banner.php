<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';
    #
    private $filedisk = 'banners';

    protected $fillable = [
        'title',
        'subtitle',
        'text_button',
        'url_button',
        'image_banner',
        'order'
    ];

    public $appends = [
        'image_url',
    ];

    /**
     * Get the image URL for the banner.
     *
     * @return string
     */
    // Accesor para obtener la URL de la imagen del banner
    public function getImageUrlAttribute()
    {
        if(Storage::disk($this->filedisk)->exists($this->image_banner)) {
             return asset (Storage::disk($this->filedisk)->url($this->image_banner));
        }
        // Si no existe la imagen, retornar una imagen por defecto
        return asset('images/default-banner.png');
    }

}
