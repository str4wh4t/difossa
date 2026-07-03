<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionRegistrationMember extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionRegistrationMemberFactory> */
    use HasFactory;

    protected $fillable = [
        'competition_registration_id',
        'name',
        'email',
        'affiliation',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(CompetitionRegistration::class, 'competition_registration_id');
    }
}
