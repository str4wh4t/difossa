<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

class FeaturedImageService
{
    private const DISK = 'public';

    private const THUMBNAIL_DIRECTORY = 'posts/thumbnails';

    private const SCALE = 0.3;

    private const JPEG_QUALITY = 85;

    public function createThumbnail(string $path): ?string
    {
        $disk = Storage::disk(self::DISK);

        if (! $disk->exists($path)) {
            return null;
        }

        $absolutePath = $disk->path($path);
        $image = @imagecreatefromjpeg($absolutePath);

        if ($image === false) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $thumbnailWidth = max(1, (int) round($width * self::SCALE));
        $thumbnailHeight = max(1, (int) round($height * self::SCALE));

        $thumbnail = imagescale($image, $thumbnailWidth, $thumbnailHeight);

        imagedestroy($image);

        if ($thumbnail === false) {
            return null;
        }

        $thumbnailPath = self::THUMBNAIL_DIRECTORY.'/'.basename($path);

        $disk->makeDirectory(self::THUMBNAIL_DIRECTORY);

        $saved = imagejpeg($thumbnail, $disk->path($thumbnailPath), self::JPEG_QUALITY);

        imagedestroy($thumbnail);

        if (! $saved) {
            throw new RuntimeException("Failed to save thumbnail for [{$path}].");
        }

        return $thumbnailPath;
    }

    public function delete(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        $disk = Storage::disk(self::DISK);

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }

    public function syncThumbnailForPost(?string $originalPath, ?string $existingThumbnailPath = null): ?string
    {
        if (blank($originalPath)) {
            $this->delete($existingThumbnailPath);

            return null;
        }

        $this->delete($existingThumbnailPath);

        return $this->createThumbnail($originalPath);
    }

    public static function thumbnailPathFor(string $originalPath): string
    {
        return self::THUMBNAIL_DIRECTORY.'/'.basename($originalPath);
    }
}
