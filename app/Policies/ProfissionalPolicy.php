<?php

namespace App\Policies;

use App\Models\Profissional;
use App\Models\User;
use App\Permissions\ProfissionalPermissions;

class ProfissionalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(ProfissionalPermissions::ViewAny->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Profissional $permission): bool
    {
        return $user->hasPermissionTo(ProfissionalPermissions::View->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(ProfissionalPermissions::Create->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Profissional $permission): bool
    {
        return $user->hasPermissionTo(ProfissionalPermissions::Update->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Profissional $permission): bool
    {
        return $user->hasPermissionTo(ProfissionalPermissions::Delete->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Profissional $permission): bool
    {
        return $user->hasPermissionTo(ProfissionalPermissions::Restore->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Profissional $permission): bool
    {
        return $user->hasPermissionTo(ProfissionalPermissions::ForceDelete->value);
    }
}
