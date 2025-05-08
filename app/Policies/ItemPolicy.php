<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResourcePolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Resource $resource): bool
    {
        // Super admin can manage all resources
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin can only manage resources in their company
        if ($user->isAdmin()) {
            return $resource->company_id === $user->company_id;
        }

        return false;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    public function view(User $user, Resource $resource): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $resource->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    public function update(User $user, Resource $resource): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $resource->company_id === $user->company_id;
        }

        return false;
    }

    public function delete(User $user, Resource $resource): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $resource->company_id === $user->company_id;
        }

        return false;
    }
}
