<?php

namespace App\Policies;

use App\Models\Reward;
use App\Models\User;
use App\Permissions\RewardPermissions;

class RewardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(RewardPermissions::ViewAny->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reward $permission): bool
    {
        return $user->hasPermissionTo(RewardPermissions::View->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(RewardPermissions::Create->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reward $permission): bool
    {
        return $user->hasPermissionTo(RewardPermissions::Update->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reward $permission): bool
    {
        return $user->hasPermissionTo(RewardPermissions::Delete->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reward $permission): bool
    {
        return $user->hasPermissionTo(RewardPermissions::Restore->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reward $permission): bool
    {
        return $user->hasPermissionTo(RewardPermissions::ForceDelete->value);
    }
}
