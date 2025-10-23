<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CommsLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommsLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_comms_log');
    }

    public function view(AuthUser $authUser, CommsLog $commsLog): bool
    {
        return $authUser->can('view_comms_log');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_comms_log');
    }

    public function update(AuthUser $authUser, CommsLog $commsLog): bool
    {
        return $authUser->can('update_comms_log');
    }

    public function delete(AuthUser $authUser, CommsLog $commsLog): bool
    {
        return $authUser->can('delete_comms_log');
    }

    public function restore(AuthUser $authUser, CommsLog $commsLog): bool
    {
        return $authUser->can('restore_comms_log');
    }

    public function forceDelete(AuthUser $authUser, CommsLog $commsLog): bool
    {
        return $authUser->can('force_delete_comms_log');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_comms_log');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_comms_log');
    }

    public function replicate(AuthUser $authUser, CommsLog $commsLog): bool
    {
        return $authUser->can('replicate_comms_log');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_comms_log');
    }

}