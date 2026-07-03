<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CompetitionRegistration;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CompetitionRegistrationPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CompetitionRegistration');
    }

    public function view(AuthUser $authUser, CompetitionRegistration $competitionRegistration): bool
    {
        if (! $authUser->can('View:CompetitionRegistration')) {
            return false;
        }

        if ($authUser instanceof User && $authUser->canManageCompetitions()) {
            return true;
        }

        return $competitionRegistration->user_id === $authUser->id;
    }

    public function create(AuthUser $authUser): bool
    {
        if (! $authUser->can('Create:CompetitionRegistration')) {
            return false;
        }

        if ($authUser instanceof User && ! $authUser->canManageCompetitions() && ! $authUser->hasParticipantProfile()) {
            return false;
        }

        return true;
    }

    public function update(AuthUser $authUser, CompetitionRegistration $competitionRegistration): bool
    {
        if (! $authUser->can('Update:CompetitionRegistration')) {
            return false;
        }

        if (! $this->registrationCompetitionIsOpen($competitionRegistration)) {
            return false;
        }

        if ($authUser instanceof User && $authUser->canManageCompetitions()) {
            return true;
        }

        return $competitionRegistration->user_id === $authUser->id;
    }

    public function delete(AuthUser $authUser, CompetitionRegistration $competitionRegistration): bool
    {
        if (! $authUser->can('Delete:CompetitionRegistration')) {
            return false;
        }

        if ($competitionRegistration->hasWinner()) {
            return false;
        }

        if (! $this->registrationCompetitionIsOpen($competitionRegistration)) {
            return false;
        }

        if ($authUser instanceof User && $authUser->canManageCompetitions()) {
            return true;
        }

        return $competitionRegistration->user_id === $authUser->id;
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CompetitionRegistration');
    }

    public function restore(AuthUser $authUser, CompetitionRegistration $competitionRegistration): bool
    {
        return $authUser->can('Restore:CompetitionRegistration');
    }

    public function forceDelete(AuthUser $authUser, CompetitionRegistration $competitionRegistration): bool
    {
        if (! $authUser->can('ForceDelete:CompetitionRegistration')) {
            return false;
        }

        if ($competitionRegistration->hasWinner()) {
            return false;
        }

        return $this->registrationCompetitionIsOpen($competitionRegistration);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CompetitionRegistration');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CompetitionRegistration');
    }

    public function replicate(AuthUser $authUser, CompetitionRegistration $competitionRegistration): bool
    {
        return $authUser->can('Replicate:CompetitionRegistration');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CompetitionRegistration');
    }

    protected function registrationCompetitionIsOpen(CompetitionRegistration $competitionRegistration): bool
    {
        $competitionRegistration->loadMissing('competition.status');

        return $competitionRegistration->competition?->status?->isOpen() ?? false;
    }
}
