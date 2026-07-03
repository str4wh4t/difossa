<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CompetitionStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompetitionStatusPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CompetitionStatus');
    }

    public function view(AuthUser $authUser, CompetitionStatus $competitionStatus): bool
    {
        return $authUser->can('View:CompetitionStatus');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CompetitionStatus');
    }

    public function update(AuthUser $authUser, CompetitionStatus $competitionStatus): bool
    {
        return $authUser->can('Update:CompetitionStatus');
    }

    public function delete(AuthUser $authUser, CompetitionStatus $competitionStatus): bool
    {
        if ($competitionStatus->hasCompetitions()) {
            return false;
        }

        return $authUser->can('Delete:CompetitionStatus');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CompetitionStatus');
    }

    public function restore(AuthUser $authUser, CompetitionStatus $competitionStatus): bool
    {
        return $authUser->can('Restore:CompetitionStatus');
    }

    public function forceDelete(AuthUser $authUser, CompetitionStatus $competitionStatus): bool
    {
        if ($competitionStatus->hasCompetitions()) {
            return false;
        }

        return $authUser->can('ForceDelete:CompetitionStatus');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CompetitionStatus');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CompetitionStatus');
    }

    public function replicate(AuthUser $authUser, CompetitionStatus $competitionStatus): bool
    {
        return $authUser->can('Replicate:CompetitionStatus');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CompetitionStatus');
    }

}