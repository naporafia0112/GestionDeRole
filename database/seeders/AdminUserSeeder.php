<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère les rôles par leur nom
        $adminRole = Role::where('name', 'ADMIN')->first();
        $rhRole = Role::where('name', 'RH')->first();

        // 1️⃣ Création de l'utilisateur ADMIN
        if (!User::where('email', 'naporafia0@gmail.com')->exists()) {
            $admin = User::create([
                'name' => 'Rafia',
                'email' => 'naporafia0@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('Rafia0112!'),
            ]);

            // Lier le rôle ADMIN
            if ($adminRole) {
                $admin->roles()->attach($adminRole->id);
            }
        }

        // 2️⃣ Création de l'utilisateur RH
        if (!User::where('email', 'rh@test.com')->exists()) {
            $rh = User::create([
                'name' => 'RH Responsable',
                'email' => 'rh@test.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('Password123!'),
            ]);

            // Lier le rôle RH
            if ($rhRole) {
                $rh->roles()->attach($rhRole->id);
            }
        }
    }
}
