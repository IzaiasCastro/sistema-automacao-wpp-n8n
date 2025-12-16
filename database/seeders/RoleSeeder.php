<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Organization::query()->each(function (Organization $organization) {
            $organization->roles()->create([
                'name' => 'Admin',
                'guard_name' => 'web',
            ]);

            $organization->roles()->create([
                'name' => 'Propietario',
                'guard_name' => 'web',
            ]);

            $organization->roles()->create([
                'name' => 'Profissional',
                'guard_name' => 'web',
            ]);

            $organization->roles()->create([
                'name' => 'Cliente',
                'guard_name' => 'web',
            ]);
        });

    }
}
