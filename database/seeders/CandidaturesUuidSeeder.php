<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Candidature;
use Illuminate\Support\Str;

class CandidaturesUuidSeeder extends Seeder
{
    public function run()
    {
        Candidature::whereNull('uuid')->chunk(100, function ($candidatures) {
            foreach ($candidatures as $candidature) {
                $candidature->uuid = (string) Str::uuid();
                $candidature->save();
            }
        });

        $this->command->info('UUID générés pour les candidatures sans uuid.');
    }
}
