<?php

namespace App\Policies;

use App\Models\PointTransaction;
use App\Models\User;
use App\Permissions\PointTransactionPermissions;

class PointTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PointTransactionPermissions::ViewAny->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PointTransaction $permission): bool
    {
        return $user->hasPermissionTo(PointTransactionPermissions::View->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PointTransactionPermissions::Create->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PointTransaction $permission): bool
    {
        return $user->hasPermissionTo(PointTransactionPermissions::Update->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PointTransaction $permission): bool
    {
        return $user->hasPermissionTo(PointTransactionPermissions::Delete->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PointTransaction $permission): bool
    {
        return $user->hasPermissionTo(PointTransactionPermissions::Restore->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PointTransaction $permission): bool
    {
        return $user->hasPermissionTo(PointTransactionPermissions::ForceDelete->value);
    }
}
