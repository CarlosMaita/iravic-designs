<?php

namespace App\Services\Images;

use Image;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Almacena una imagen un disco especificado por parametro
     */
    public static function save($disk, $file , $extension = 'jpg')
    {
        if ($file) {
            $image = Image::make($file)->encode($extension,80);
            $filename = $disk . '_' . time() . '_' . uniqid() . '.' . $extension;
            Storage::disk($disk)->put($filename, $image);

            return $filename;
        }

        return null;
    }

    /**
     * Procesa actualizacion de una imagen (Manda a eliminar una imagen si existe para luego almacena una nueva).
     */
    public static function updateImage($disk, $old_image, $new_file, $delete)
    {
        $url = null;

        if ($delete || $new_file) {
            self::delete($disk, $old_image);
        }

        if ($new_file) {
            $url = self::save($disk, $new_file);
        } else if (!$delete) {
            $url = $old_image;
        }

        return $url;
    }
    
    /**
     * Elimina una imagen de un disco especificado por parametro
     */
    public static function delete($disk, $image)
    {
        if (Storage::disk($disk)->exists($image)) {
            Storage::disk($disk)->delete($image);
        }
    }
}