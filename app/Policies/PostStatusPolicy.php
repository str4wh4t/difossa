<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PostStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostStatusPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PostStatus');
    }

    public function view(AuthUser $authUser, PostStatus $postStatus): bool
    {
        return $authUser->can('View:PostStatus');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PostStatus');
    }

    public function update(AuthUser $authUser, PostStatus $postStatus): bool
    {
        return $authUser->can('Update:PostStatus');
    }

    public function delete(AuthUser $authUser, PostStatus $postStatus): bool
    {
        if ($postStatus->hasPosts()) {
            return false;
        }

        return $authUser->can('Delete:PostStatus');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PostStatus');
    }

    public function restore(AuthUser $authUser, PostStatus $postStatus): bool
    {
        return $authUser->can('Restore:PostStatus');
    }

    public function forceDelete(AuthUser $authUser, PostStatus $postStatus): bool
    {
        if ($postStatus->hasPosts()) {
            return false;
        }

        return $authUser->can('ForceDelete:PostStatus');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PostStatus');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PostStatus');
    }

    public function replicate(AuthUser $authUser, PostStatus $postStatus): bool
    {
        return $authUser->can('Replicate:PostStatus');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PostStatus');
    }

}