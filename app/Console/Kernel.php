<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Planifie les tâches artisan.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Ta commande artisan pour publier/désactiver les offres
        $schedule->command('app:traiter-offres')->dailyAt('00:00'); // Exécution tous les jours à minuit
        // Pour les tests rapides : utilise plutôt → ->everyMinute();
    }

    /**
     * Charge les commandes artisan personnalisées.
     */
    protected function commands(): void
    {
        // Charge automatiquement toutes les commandes dans app/Console/Commands/
        $this->load(__DIR__.'/Commands');

        // Charge aussi les commandes déclarées dans routes/console.php si tu en ajoutes un jour
        require base_path('routes/console.php');
    }
}
