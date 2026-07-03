<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Competition;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompetitionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Competition');
    }

    public function view(AuthUser $authUser, Competition $competition): bool
    {
        return $authUser->can('View:Competition');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Competition');
    }

    public function update(AuthUser $authUser, Competition $competition): bool
    {
        return $authUser->can('Update:Competition');
    }

    public function delete(AuthUser $authUser, Competition $competition): bool
    {
        if ($competition->hasRegistrations()) {
            return false;
        }

        return $authUser->can('Delete:Competition');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Competition');
    }

    public function restore(AuthUser $authUser, Competition $competition): bool
    {
        return $authUser->can('Restore:Competition');
    }

    public function forceDelete(AuthUser $authUser, Competition $competition): bool
    {
        if ($competition->hasRegistrations()) {
            return false;
        }

        return $authUser->can('ForceDelete:Competition');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Competition');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Competition');
    }

    public function replicate(AuthUser $authUser, Competition $competition): bool
    {
        return $authUser->can('Replicate:Competition');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Competition');
    }

}