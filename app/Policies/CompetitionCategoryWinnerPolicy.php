<?php

namespace App\Policies;

use App\Models\CompetitionCategoryWinner;
use App\Models\User;

class CompetitionCategoryWinnerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageCompetitions();
    }

    public function view(User $user, CompetitionCategoryWinner $competitionCategoryWinner): bool
    {
        return $user->canManageCompetitions();
    }

    public function create(User $user): bool
    {
        return $user->canManageCompetitions();
    }

    public function update(User $user, CompetitionCategoryWinner $competitionCategoryWinner): bool
    {
        return $user->canManageCompetitions();
    }

    public function delete(User $user, CompetitionCategoryWinner $competitionCategoryWinner): bool
    {
        return $user->canManageCompetitions();
    }
}
