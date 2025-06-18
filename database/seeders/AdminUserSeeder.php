<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifie si l'utilisateur existe déjà
        if (!User::where('email', 'admin@test.com')->exists()) {
            $user = User::create([
                'name' => 'Rafia',
                'email' => 'naporafia0@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('Rafia0112!'), // Assure-toi de hacher le mot de passe
            ]);

            // Si tu utilises les rôles
            // $user->roles()->attach(1); // ID du rôle admin, par exemple
        }
    }
}
