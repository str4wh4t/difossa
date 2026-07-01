<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function booted(): void
    {
        static::saving(function (Menu $menu): void {
            if (blank($menu->slug) && filled($menu->name)) {
                $menu->slug = Str::slug($menu->name);
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public static function findBySlugWithItems(string $slug): ?self
    {
        return static::query()
            ->where('slug', $slug)
            ->with([
                'items' => fn ($query) => $query->with([
                    'linkable',
                    'children' => fn ($children) => $children->with('linkable'),
                ]),
            ])
            ->first();
    }
}
