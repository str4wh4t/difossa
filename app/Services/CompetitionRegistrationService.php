<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\CompetitionCategory;
use App\Models\CompetitionRegistration;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CompetitionRegistrationService
{
    public function assertUserCanRegister(
        User $user,
        Competition $competition,
        CompetitionCategory $category,
    ): void {
        if (! $user->hasParticipantProfile()) {
            throw ValidationException::withMessages([
                'profile' => 'Please complete your full name and affiliation in your profile before registering.',
            ]);
        }

        if (! $competition->isOpenForRegistration()) {
            throw ValidationException::withMessages([
                'competition_id' => 'This competition is not open for registration.',
            ]);
        }

        $exists = CompetitionRegistration::query()
            ->where('user_id', $user->id)
            ->where('competition_id', $competition->id)
            ->where('competition_category_id', $category->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'competition_category_id' => 'You have already registered for this category in this competition.',
            ]);
        }
    }

    public function assertCanAssignWinner(Competition $competition): void
    {
        if (! $competition->isFinished()) {
            throw ValidationException::withMessages([
                'rank' => 'Winners can only be assigned when the competition status is Finished.',
            ]);
        }
    }

    public function assertRegistrationBelongsToCategory(
        Competition $competition,
        CompetitionCategory $category,
        int $registrationId,
    ): void {
        $belongs = CompetitionRegistration::query()
            ->whereKey($registrationId)
            ->where('competition_id', $competition->id)
            ->where('competition_category_id', $category->id)
            ->exists();

        if (! $belongs) {
            throw ValidationException::withMessages([
                'competition_registration_id' => 'The selected registration does not belong to this category in this competition.',
            ]);
        }
    }
}
