<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CompetitionCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompetitionCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CompetitionCategory');
    }

    public function view(AuthUser $authUser, CompetitionCategory $competitionCategory): bool
    {
        return $authUser->can('View:CompetitionCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CompetitionCategory');
    }

    public function update(AuthUser $authUser, CompetitionCategory $competitionCategory): bool
    {
        return $authUser->can('Update:CompetitionCategory');
    }

    public function delete(AuthUser $authUser, CompetitionCategory $competitionCategory): bool
    {
        if ($competitionCategory->hasRegistrations()) {
            return false;
        }

        return $authUser->can('Delete:CompetitionCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CompetitionCategory');
    }

    public function restore(AuthUser $authUser, CompetitionCategory $competitionCategory): bool
    {
        return $authUser->can('Restore:CompetitionCategory');
    }

    public function forceDelete(AuthUser $authUser, CompetitionCategory $competitionCategory): bool
    {
        if ($competitionCategory->hasRegistrations()) {
            return false;
        }

        return $authUser->can('ForceDelete:CompetitionCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CompetitionCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CompetitionCategory');
    }

    public function replicate(AuthUser $authUser, CompetitionCategory $competitionCategory): bool
    {
        return $authUser->can('Replicate:CompetitionCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CompetitionCategory');
    }

}