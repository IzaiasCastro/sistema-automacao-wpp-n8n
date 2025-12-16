<?php

namespace App\Policies;

use App\Models\Servico;
use App\Models\User;
use App\Permissions\ServicoPermissions;

class ServicoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(ServicoPermissions::ViewAny->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Servico $permission): bool
    {
        return $user->hasPermissionTo(ServicoPermissions::View->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(ServicoPermissions::Create->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Servico $permission): bool
    {
        return $user->hasPermissionTo(ServicoPermissions::Update->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Servico $permission): bool
    {
        return $user->hasPermissionTo(ServicoPermissions::Delete->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Servico $permission): bool
    {
        return $user->hasPermissionTo(ServicoPermissions::Restore->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Servico $permission): bool
    {
        return $user->hasPermissionTo(ServicoPermissions::ForceDelete->value);
    }
}
