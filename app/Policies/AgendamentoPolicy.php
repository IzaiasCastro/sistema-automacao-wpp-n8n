<?php

namespace App\Policies;

use App\Models\Agendamento;
use App\Models\User;
use App\Permissions\AgendamentoPermissions;

class AgendamentoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(AgendamentoPermissions::ViewAny->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agendamento $permission): bool
    {
        return $user->hasPermissionTo(AgendamentoPermissions::View->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(AgendamentoPermissions::Create->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agendamento $permission): bool
    {
        return $user->hasPermissionTo(AgendamentoPermissions::Update->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agendamento $permission): bool
    {
        return $user->hasPermissionTo(AgendamentoPermissions::Delete->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agendamento $permission): bool
    {
        return $user->hasPermissionTo(AgendamentoPermissions::Restore->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agendamento $permission): bool
    {
        return $user->hasPermissionTo(AgendamentoPermissions::ForceDelete->value);
    }
}
