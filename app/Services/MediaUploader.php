<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Stores uploads on the public disk. Raster images are resized and
 * converted to WebP (GD) for fast pages; everything else is kept as is.
 */
class MediaUploader
{
    protected const RASTER = ['image/jpeg', 'image/png', 'image/webp'];

    public function image(UploadedFile $file, string $directory, ?int $maxWidth = null, bool $convert = true): string
    {
        $mime = $file->getMimeType();

        if ($convert && in_array($mime, self::RASTER, true) && function_exists('imagewebp')) {
            try {
                return $this->toWebp($file, $directory, $maxWidth ?? (int) config('site.uploads.image_max_width', 2000));
            } catch (Throwable $e) {
                report($e);
            }
        }

        return $this->file($file, $directory);
    }

    public function file(UploadedFile $file, string $directory): string
    {
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'bin');

        return $file->storeAs($this->folder($directory), $name.'-'.Str::lower(Str::random(6)).'.'.$extension, 'public');
    }

    /** Store a new upload and remove the previous file. */
    public function replace(?string $old, UploadedFile $file, string $directory, bool $image = true, ?int $maxWidth = null, bool $convert = true): string
    {
        $path = $image ? $this->image($file, $directory, $maxWidth, $convert) : $this->file($file, $directory);
        $this->delete($old);

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['seed/', 'assets/', 'http'])) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function toWebp(UploadedFile $file, string $directory, int $maxWidth): string
    {
        $source = match ($file->getMimeType()) {
            'image/jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png' => imagecreatefrompng($file->getRealPath()),
            'image/webp' => imagecreatefromwebp($file->getRealPath()),
        };

        if ($file->getMimeType() === 'image/jpeg') {
            $source = $this->applyExifOrientation($source, $file->getRealPath());
        }

        $width = imagesx($source);
        $height = imagesy($source);

        if ($width > $maxWidth) {
            $newHeight = (int) round($height * $maxWidth / $width);
            $resized = imagecreatetruecolor($maxWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        } else {
            imagepalettetotruecolor($source);
            imagealphablending($source, false);
            imagesavealpha($source, true);
        }

        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'image';
        $path = $this->folder($directory).'/'.$name.'-'.Str::lower(Str::random(6)).'.webp';

        ob_start();
        imagewebp($source, null, (int) config('site.uploads.webp_quality', 82));
        $binary = ob_get_clean();
        imagedestroy($source);

        Storage::disk('public')->put($path, $binary);

        return $path;
    }

    protected function applyExifOrientation($image, string $path)
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = @exif_read_data($path)['Orientation'] ?? 1;

        return match ((int) $orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    protected function folder(string $directory): string
    {
        return trim($directory, '/').'/'.now()->format('Y/m');
    }
}
