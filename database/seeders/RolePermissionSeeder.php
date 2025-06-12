<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Liste des permissions de base
        $permissions = [
            'voir_utilisateurs',
            'créer_utilisateur',
            'modifier_utilisateur',
            'supprimer_utilisateur',
            'voir_roles',
            'créer_role',
            'modifier_role',
            'supprimer_role',
        ];

        // Création des permissions
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Création de quelques rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'utilisateur']);

        // Attribution de toutes les permissions à l'admin
        $adminRole->permissions()->sync(Permission::all()->pluck('id'));

        // Attribution de quelques permissions au rôle utilisateur
        $userPermissions = Permission::whereIn('name', [
            'voir_utilisateurs',
            'voir_roles'
        ])->pluck('id');

        $userRole->permissions()->sync($userPermissions);
    }
}
