<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Item $item): bool
    {
        // Super admin can manage all items
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin can only manage items in their company
        if ($user->isAdmin()) {
            return $item->company_id === $user->company_id;
        }

        return false;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    public function view(User $user, Item $item): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $item->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    public function update(User $user, Item $item): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $item->company_id === $user->company_id;
        }

        return false;
    }

    public function delete(User $user, Item $item): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $item->company_id === $user->company_id;
        }

        return false;
    }
}
