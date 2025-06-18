<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Localisation;
use App\Models\Offre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\Factory;
class LocalisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pays = [
            'Togo',
            'Benin',
            'Cameroun',
            'Gabon',
            'Côte d\'Ivoire',
            'Burkina Faso',
            'Mali',
            'Niger',
            'Sénégal',
            'Rwanda',
            'Burundi',
            'RDC',
        ];

        foreach ($pays as $nom) {
            Localisation::create(['pays' => $nom]);
        }
    }
}
