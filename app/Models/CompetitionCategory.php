<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class CompetitionCategory extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::saving(function (CompetitionCategory $category): void {
            if (blank($category->slug) && filled($category->name)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CompetitionRegistration::class);
    }

    public function hasRegistrations(): bool
    {
        return $this->registrations()->exists();
    }

    public function winners(): HasMany
    {
        return $this->hasMany(CompetitionCategoryWinner::class);
    }

    public function registeredCompetitions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Competition::class,
            CompetitionRegistration::class,
            'competition_category_id',
            'id',
            'id',
            'competition_id',
        )->distinct();
    }
}
