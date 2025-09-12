<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'title',
        'subtitle',
        'text_button',
        'url_button',
        'image_banner',
        'order'
    ];

    protected $appends = [
        'image_url',
    ];

    /**
     * Get the image URL for the banner.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        $disk = 'banners';
        $image = $this->image_banner;

        if ($image && Storage::disk($disk)->exists($image)) {
            return asset(Storage::disk($disk)->url($image));
        }

        return asset('images/default-banner.png');
    }
}
