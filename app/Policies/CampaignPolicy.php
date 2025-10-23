<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Campaign;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_campaign');
    }

    public function view(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('view_campaign');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_campaign');
    }

    public function update(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('update_campaign');
    }

    public function delete(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('delete_campaign');
    }

    public function restore(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('restore_campaign');
    }

    public function forceDelete(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('force_delete_campaign');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_campaign');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_campaign');
    }

    public function replicate(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('replicate_campaign');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_campaign');
    }

}