<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage:
     *  php artisan image:make-webp public/img/login/girl.jpg --quality=82 [--output=public/img/login/girl.webp]
     */
    protected $signature = 'image:make-webp {source : Source JPG/PNG absolute or relative to project root}
                                         {--output= : Optional output path (.webp). Defaults to same name with .webp}
                                         {--quality=82 : WebP quality (0-100)}';

    /**
     * The console command description.
     */
    protected $description = 'Convert a JPG/PNG image to WebP using the GD extension (no extra packages).';

    public function handle(): int
    {
        if (!extension_loaded('gd') || !function_exists('imagewebp')) {
            $this->error('GD extension with WebP support is required. Enable GD in your PHP and ensure imagewebp() is available.');
            return 1;
        }

        $srcArg = (string) $this->argument('source');
        $src = realpath(base_path($srcArg)) ?: realpath($srcArg);
        if (!$src || !is_file($src)) {
            $this->error("Source file not found: {$srcArg}");
            return 1;
        }

        $outArg = $this->option('output');
        if ($outArg) {
            $dst = base_path($outArg);
        } else {
            $pathInfo = pathinfo($src);
            $dst = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '.webp';
        }

        $quality = (int) $this->option('quality');
        $quality = max(0, min(100, $quality));

        // Load source image
        $mime = mime_content_type($src) ?: '';
        $im = null;
        switch (strtolower($mime)) {
            case 'image/jpeg':
            case 'image/jpg':
                $im = imagecreatefromjpeg($src);
                break;
            case 'image/png':
                $im = imagecreatefrompng($src);
                // Preserve alpha for PNG
                if ($im) {
                    imagepalettetotruecolor($im);
                    imagealphablending($im, false);
                    imagesavealpha($im, true);
                }
                break;
            case 'image/webp':
                $this->warn('Source is already WebP. Copying...');
                return copy($src, $dst) ? 0 : 1;
            default:
                $this->error('Unsupported source mime type: ' . $mime . ' (only JPG/PNG supported)');
                return 1;
        }

        if (!$im) {
            $this->error('Failed to load source image.');
            return 1;
        }

        // Ensure directory exists
        $dir = dirname($dst);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0775, true) && !is_dir($dir)) {
                $this->error('Failed to create output directory: ' . $dir);
                imagedestroy($im);
                return 1;
            }
        }

        // Save as WebP
        $ok = imagewebp($im, $dst, $quality);
        imagedestroy($im);

        if (!$ok) {
            $this->error('Failed to write WebP to: ' . $dst);
            return 1;
        }

        $this->info('Created: ' . $dst . ' (quality=' . $quality . ')');
        return 0;
    }
}
