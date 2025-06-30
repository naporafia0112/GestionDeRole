<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Création des rôles
        $roles = ['ADMIN', 'RH', 'DIRECTEUR', 'TUTEUR'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Création des permissions
        $permissions = [
            'voir_utilisateurs',
            'créer_utilisateur',
            'modifier_utilisateur',
            'supprimer_utilisateur',
            'voir_candidatures',
            'valider_stages',
            'attribuer_tuteur',
            'publier_offres',
            'analyser_candidatures'
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName]);
        }

        // Donner toutes les permissions au rôle ADMIN
        $admin = Role::where('name', 'ADMIN')->first();
        $allPermissions = Permission::pluck('id');
        $admin->permissions()->sync($allPermissions);
    }
}
