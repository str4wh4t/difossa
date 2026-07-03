<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class CompetitionRegistration extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionRegistrationFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'competition_id',
        'competition_category_id',
        'article_title',
        'article_summary',
        'article_file',
    ];

    protected static function booted(): void
    {
        static::deleting(function (CompetitionRegistration $registration): void {
            if (filled($registration->article_file)) {
                Storage::disk('public')->delete($registration->article_file);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CompetitionCategory::class, 'competition_category_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(CompetitionRegistrationMember::class);
    }

    public function winner(): HasOne
    {
        return $this->hasOne(CompetitionCategoryWinner::class, 'competition_registration_id');
    }

    public function hasWinner(): bool
    {
        return $this->winner()->exists();
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function getArticleFileUrlAttribute(): ?string
    {
        if (blank($this->article_file)) {
            return null;
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->article_file);
    }

    public function getArticleFileDownloadUrlAttribute(): ?string
    {
        if (blank($this->article_file)) {
            return null;
        }

        return route('competition-registrations.article.download', $this);
    }
}
