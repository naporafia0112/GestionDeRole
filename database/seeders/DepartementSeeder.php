<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;

class DepartementSeeder extends Seeder
{
    public function run()
    {
        Departement::create(['nom' => 'Informatique', 'description' => 'Département informatique']);
        Departement::create(['nom' => 'Ressources Humaines', 'description' => 'Département RH']);
        Departement::create(['nom' => 'Marketing', 'description' => 'Département Marketing']);
    }
}
