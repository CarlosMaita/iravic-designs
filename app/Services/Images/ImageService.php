<?php

namespace App\Services\Images;

use Image;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * 
     */
    public static function save($disk, $file)
    {
        if ($file) {
            $image = Image::make($file)->encode('jpg',80);
            $filename = $disk . '_' . time() . '_' . uniqid() . '.jpg';
            Storage::disk($disk)->put($filename, $image);

            return $filename;
        }

        return null;
    }
    
    /**
     * 
     */
    public static function delete($disk, $image)
    {
        if (Storage::disk($disk)->exists($image)) {
            Storage::disk($disk)->delete($image);
        }
    }
}