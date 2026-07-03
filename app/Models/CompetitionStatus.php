<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionStatus extends Model
{
    public const SLUG_DRAFT = 'draft';

    public const SLUG_OPEN = 'open';

    public const SLUG_CLOSED = 'closed';

    public const SLUG_FINISHED = 'finished';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }

    public function hasCompetitions(): bool
    {
        return $this->competitions()->exists();
    }

    public function isOpen(): bool
    {
        return $this->slug === self::SLUG_OPEN;
    }

    public function isFinished(): bool
    {
        return $this->slug === self::SLUG_FINISHED;
    }

    public static function idForSlug(string $slug): ?int
    {
        return static::query()->where('slug', $slug)->value('id');
    }
}
