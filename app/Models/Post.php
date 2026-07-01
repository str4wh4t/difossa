<?php

namespace App\Models;

use App\Services\FeaturedImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_status_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_thumbnail',
        'published_at',
        'is_sticky',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_sticky' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Post $post): void {
            if (blank($post->slug) && filled($post->title)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::saved(function (Post $post): void {
            if (! $post->wasChanged('featured_image')) {
                return;
            }

            $featuredImageService = app(FeaturedImageService::class);
            $thumbnail = $featuredImageService->syncThumbnailForPost(
                $post->featured_image,
                $post->getOriginal('featured_image_thumbnail'),
            );

            if ($thumbnail !== $post->featured_image_thumbnail) {
                $post->forceFill(['featured_image_thumbnail' => $thumbnail])->saveQuietly();
            }
        });

        static::deleting(function (Post $post): void {
            $featuredImageService = app(FeaturedImageService::class);

            $featuredImageService->delete($post->featured_image);
            $featuredImageService->delete($post->featured_image_thumbnail);
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(PostStatus::class, 'post_status_id');
    }

    public function menuItems(): MorphMany
    {
        return $this->morphMany(MenuItem::class, 'linkable');
    }

    public function scopePublished($query)
    {
        return $query->whereHas('status', fn ($q) => $q->where('slug', 'published'))
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeSticky($query)
    {
        return $query->where('is_sticky', true);
    }

    public static function recentForSidebar(?int $exceptId = null, int $limit = 5)
    {
        return static::query()
            ->published()
            ->when($exceptId, fn ($query) => $query->whereKeyNot($exceptId))
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function resolveRouteBinding($value, $field = null): Model
    {
        return $this->published()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (blank($this->featured_image_thumbnail)) {
            return null;
        }

        return Storage::disk('public')->url($this->featured_image_thumbnail);
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (blank($this->featured_image)) {
            return null;
        }

        return Storage::disk('public')->url($this->featured_image);
    }

    public function getMetaTitleAttribute(?string $value): ?string
    {
        return filled($value) ? $value : $this->title;
    }
}
