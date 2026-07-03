<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionCategoryWinner extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionCategoryWinnerFactory> */
    use HasFactory;

    protected $fillable = [
        'competition_category_id',
        'competition_registration_id',
        'rank',
    ];

    protected function casts(): array
    {
        return [
            'rank' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CompetitionCategory::class, 'competition_category_id');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(CompetitionRegistration::class, 'competition_registration_id');
    }
}
