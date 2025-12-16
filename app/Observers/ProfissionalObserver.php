<?php

namespace App\Observers;

use App\Models\Profissional;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfissionalObserver
{
    /**
     * Handle the Cliente "created" event.
     */
    public function creating(Profissional $profissional): void
    {
        // Se ainda não existir user_id
        if (!$profissional->user_id) {
            $user = User::create([
                'name' => $profissional->nome ?? 'Profissional',
                'email' => $profissional->email ?? 'profissional' . uniqid() . '@agendaeganhe.com',
                'password' => Hash::make('12345678'), // senha padrão
            ]);

            //colocar esse usaurio em um role
            $findRoleOrganization = Role::where('name', 'Profissional')->where('organization_id', $profissional->organization_id)->first();
            DB::table('model_has_roles')->insert([
                'model_id' => $user->id,
                'role_id' => $findRoleOrganization->id,
                'model_type' => 'App\Models\User',
                'organization_id' => $profissional->organization_id
            ]);

            //criar vinculo na tabela organization_user
            DB::table('organization_user')->insert([
                'organization_id' => $profissional->organization_id,
                'user_id' => $user->id,
            ]);

            $profissional->user_id = $user->id;
        }
    }
    public function created(Profissional $profissional): void
    {
        //
    }

    /**
     * Handle the Cliente "updated" event.
     */
    public function updated(Profissional $profissional): void
    {
        //
    }

    /**
     * Handle the Cliente "deleted" event.
     */
    public function deleted(Profissional $profissional): void
    {
        //
    }

    /**
     * Handle the Cliente "restored" event.
     */
    public function restored(Profissional $profissional): void
    {
        //
    }

    /**
     * Handle the Cliente "force deleted" event.
     */
    public function forceDeleted(Profissional $profissional): void
    {
        //
    }
}
