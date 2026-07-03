<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

class FeaturedImageService
{
    private const DISK = 'public';

    public const POST_THUMBNAIL_DIRECTORY = 'posts/thumbnails';

    public const COMPETITION_THUMBNAIL_DIRECTORY = 'competitions/thumbnails';

    private const SCALE = 0.3;

    private const JPEG_QUALITY = 85;

    public function createThumbnail(string $path, string $thumbnailDirectory = self::POST_THUMBNAIL_DIRECTORY): ?string
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

        $thumbnailPath = $thumbnailDirectory.'/'.basename($path);

        $disk->makeDirectory($thumbnailDirectory);

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

    public function syncThumbnail(
        ?string $originalPath,
        ?string $existingThumbnailPath = null,
        string $thumbnailDirectory = self::POST_THUMBNAIL_DIRECTORY,
    ): ?string {
        if (blank($originalPath)) {
            $this->delete($existingThumbnailPath);

            return null;
        }

        $this->delete($existingThumbnailPath);

        return $this->createThumbnail($originalPath, $thumbnailDirectory);
    }

    public function syncThumbnailForPost(?string $originalPath, ?string $existingThumbnailPath = null): ?string
    {
        return $this->syncThumbnail($originalPath, $existingThumbnailPath, self::POST_THUMBNAIL_DIRECTORY);
    }

    public function syncThumbnailForCompetition(?string $originalPath, ?string $existingThumbnailPath = null): ?string
    {
        return $this->syncThumbnail($originalPath, $existingThumbnailPath, self::COMPETITION_THUMBNAIL_DIRECTORY);
    }

    public static function thumbnailPathFor(string $originalPath, string $thumbnailDirectory = self::POST_THUMBNAIL_DIRECTORY): string
    {
        return $thumbnailDirectory.'/'.basename($originalPath);
    }
}
