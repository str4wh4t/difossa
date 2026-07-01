<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'url',
        'linkable_type',
        'linkable_id',
        'sort_order',
        'target',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    public function resolveUrl(): ?string
    {
        if ($this->linkable instanceof Post) {
            return route('posts.show', $this->linkable);
        }

        if ($this->linkable instanceof Page) {
            return route('pages.show', $this->linkable);
        }

        return filled($this->url) ? $this->url : null;
    }

    public function opensInNewTab(): bool
    {
        return $this->target === '_blank';
    }
}
