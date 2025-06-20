<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Offre;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OffreExpireeNotification;

class TraiterOffres extends Command
{
    protected $signature = 'app:traiter-offres';
    protected $description = 'Publie automatiquement les offres à la bonne date et désactive celles expirées';

    public function handle()
    {
        $now = now()->startOfDay();

        // 1. Publier automatiquement les offres programmées
        $offresPubliees = Offre::where('est_publie', false)
            ->whereDate('date_publication', '<=', $now)
            ->get();

        foreach ($offresPubliees as $offre) {
            $offre->est_publie = true;
            $offre->save();
            $this->info("Offre publiée automatiquement : {$offre->titre}");
        }

        // 2. Désactiver les offres expirées
        $offresExpirees = Offre::where('est_publie', true)
            ->whereDate('date_limite', '<', $now)
            ->get();

        foreach ($offresExpirees as $offre) {
            $offre->est_publie = false;
            $offre->save();

            // 3. Envoyer la notification à l'utilisateur (si offre liée à un user)
            if ($offre->user) {
                $offre->user->notify(new OffreExpireeNotification($offre));
            }

            $this->info("Offre expirée désactivée : {$offre->titre}");
        }

        $this->info("Traitement des offres terminé.");
    }
}
