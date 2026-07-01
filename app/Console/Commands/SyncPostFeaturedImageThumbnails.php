<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\FeaturedImageService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('posts:sync-featured-image-thumbnails')]
#[Description('Generate 30% thumbnails for post featured images')]
class SyncPostFeaturedImageThumbnails extends Command
{
    public function handle(FeaturedImageService $featuredImageService): int
    {
        $synced = 0;

        Post::query()
            ->whereNotNull('featured_image')
            ->cursor()
            ->each(function (Post $post) use ($featuredImageService, &$synced): void {
                $thumbnail = $featuredImageService->syncThumbnailForPost(
                    $post->featured_image,
                    $post->featured_image_thumbnail,
                );

                if ($thumbnail !== $post->featured_image_thumbnail) {
                    $post->forceFill(['featured_image_thumbnail' => $thumbnail])->saveQuietly();
                    $synced++;
                }
            });

        $this->info("Synced {$synced} post thumbnail(s).");

        return self::SUCCESS;
    }
}
