<?php

namespace App\Policies;

use App\Models\SessaoWhatsapp;
use App\Models\User;
use App\Permissions\SessaoWhatsappPermissions;

class SessaoWhatsappPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(SessaoWhatsappPermissions::ViewAny->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SessaoWhatsapp $permission): bool
    {
        return $user->hasPermissionTo(SessaoWhatsappPermissions::View->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(SessaoWhatsappPermissions::Create->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SessaoWhatsapp $permission): bool
    {
        return $user->hasPermissionTo(SessaoWhatsappPermissions::Update->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SessaoWhatsapp $permission): bool
    {
        return $user->hasPermissionTo(SessaoWhatsappPermissions::Delete->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SessaoWhatsapp $permission): bool
    {
        return $user->hasPermissionTo(SessaoWhatsappPermissions::Restore->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SessaoWhatsapp $permission): bool
    {
        return $user->hasPermissionTo(SessaoWhatsappPermissions::ForceDelete->value);
    }
}
