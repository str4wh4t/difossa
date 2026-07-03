<?php

namespace App\Models;

use App\Services\FeaturedImageService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Competition extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'banner_image',
        'banner_image_thumbnail',
        'competition_status_id',
        'registration_starts_at',
        'registration_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'registration_starts_at' => 'datetime',
            'registration_ends_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Competition $competition): void {
            if (blank($competition->slug) && filled($competition->title)) {
                $competition->slug = Str::slug($competition->title);
            }
        });

        static::created(function (Competition $competition): void {
            static::syncBannerImageThumbnail($competition);
        });

        static::updated(function (Competition $competition): void {
            if (! $competition->wasChanged('banner_image')) {
                return;
            }

            static::syncBannerImageThumbnail($competition);
        });

        static::deleting(function (Competition $competition): void {
            $featuredImageService = app(FeaturedImageService::class);

            $featuredImageService->delete($competition->banner_image);
            $featuredImageService->delete($competition->banner_image_thumbnail);
        });
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(CompetitionStatus::class, 'competition_status_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CompetitionRegistration::class);
    }

    public function hasRegistrations(): bool
    {
        return $this->registrations()->exists();
    }

    public function scopePubliclyVisible($query)
    {
        return $query->whereHas(
            'status',
            fn ($query) => $query->where('slug', '!=', CompetitionStatus::SLUG_DRAFT),
        );
    }

    public function scopeOpenForRegistration($query)
    {
        return $query
            ->whereHas('status', fn ($query) => $query->where('slug', CompetitionStatus::SLUG_OPEN))
            ->where(function ($query) {
                $query->whereNull('registration_starts_at')
                    ->orWhere('registration_starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('registration_ends_at')
                    ->orWhere('registration_ends_at', '>=', now());
            });
    }

    public function scopeNewestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }

    public function isOpenForRegistration(): bool
    {
        if (! $this->relationLoaded('status')) {
            $this->load('status');
        }

        if (! $this->status?->isOpen()) {
            return false;
        }

        if ($this->registration_starts_at && $this->registration_starts_at->isFuture()) {
            return false;
        }

        if ($this->registration_ends_at && $this->registration_ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function isFinished(): bool
    {
        if (! $this->relationLoaded('status')) {
            $this->load('status');
        }

        return $this->status?->isFinished() ?? false;
    }

    public function resolveRouteBinding($value, $field = null): Model
    {
        return $this->publiclyVisible()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function recentForSidebar(?int $exceptId = null, int $limit = 5): Collection
    {
        return static::query()
            ->publiclyVisible()
            ->with('status')
            ->when($exceptId, fn ($query) => $query->whereKeyNot($exceptId))
            ->newestFirst()
            ->limit($limit)
            ->get();
    }

    public function getBannerImageUrlAttribute(): ?string
    {
        if (blank($this->banner_image)) {
            return null;
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->banner_image);
    }

    public function getBannerThumbnailUrlAttribute(): ?string
    {
        if (blank($this->banner_image_thumbnail)) {
            return null;
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->banner_image_thumbnail);
    }

    protected static function syncBannerImageThumbnail(Competition $competition): void
    {
        $featuredImageService = app(FeaturedImageService::class);
        $thumbnail = $featuredImageService->syncThumbnailForCompetition(
            $competition->banner_image,
            $competition->banner_image_thumbnail,
        );

        if ($thumbnail !== $competition->banner_image_thumbnail) {
            $competition->forceFill(['banner_image_thumbnail' => $thumbnail])->saveQuietly();
        }
    }
}
