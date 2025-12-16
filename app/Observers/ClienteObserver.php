<?php

namespace App\Observers;

use App\Models\Cliente;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteObserver
{
    /**
     * Handle the Cliente "created" event.
     */
    public function creating(Cliente $cliente): void
    {
        // Se ainda não existir user_id
        if (!$cliente->user_id) {
            $user = User::create([
                'name' => $cliente->nome ?? 'Cliente',
                'email' => $cliente->email ?? 'cliente' . uniqid() . '@agendaeganhe.com',
                'password' => Hash::make('12345678'), // senha padrão
            ]);

            //colocar esse usaurio em um role
            $findRoleOrganization = Role::where('name', 'Cliente')->where('organization_id', $cliente->organization_id)->first();
            DB::table('model_has_roles')->insert([
                'model_id' => $user->id,
                'role_id' => $findRoleOrganization->id,
                'model_type' => 'App\Models\User',
                'organization_id' => $cliente->organization_id
            ]);

            //criar vinculo na tabela organization_user
            DB::table('organization_user')->insert([
                'organization_id' => $cliente->organization_id,
                'user_id' => $user->id,
            ]);

            $cliente->user_id = $user->id;
        }
    }
    public function created(Cliente $cliente): void
    {
        //
    }

    /**
     * Handle the Cliente "updated" event.
     */
    public function updated(Cliente $cliente): void
    {
        //
    }

    /**
     * Handle the Cliente "deleted" event.
     */
    public function deleted(Cliente $cliente): void
    {
        //
    }

    /**
     * Handle the Cliente "restored" event.
     */
    public function restored(Cliente $cliente): void
    {
        //
    }

    /**
     * Handle the Cliente "force deleted" event.
     */
    public function forceDeleted(Cliente $cliente): void
    {
        //
    }
}
