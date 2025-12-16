<?php

namespace App\Policies;

use App\Models\Agenda;
use App\Models\User;
use App\Permissions\AgendaPermissions;

class AgendaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(AgendaPermissions::ViewAny->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agenda $permission): bool
    {
        return $user->hasPermissionTo(AgendaPermissions::View->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(AgendaPermissions::Create->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agenda $permission): bool
    {
        return $user->hasPermissionTo(AgendaPermissions::Update->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agenda $permission): bool
    {
        return $user->hasPermissionTo(AgendaPermissions::Delete->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agenda $permission): bool
    {
        return $user->hasPermissionTo(AgendaPermissions::Restore->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agenda $permission): bool
    {
        return $user->hasPermissionTo(AgendaPermissions::ForceDelete->value);
    }
}
