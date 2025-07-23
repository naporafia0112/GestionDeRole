<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Stage;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
        $directeur = Auth::user();

        $nombreStagesAttente = 0;
        if ($directeur && $directeur->hasRole('DIRECTEUR')) {
            $nombreStagesAttente = Stage::whereNull('id_tuteur')
                ->where('id_departement', $directeur->id_departement)
                ->count();
        }

        $notifications = [];

        $user = Auth::user();
        if ($user) {
            if ($user->hasRole('DIRECTEUR')) {
                $departementId = $user->id_departement;

                $countEnAttente = \App\Models\Stage::where('id_departement', $departementId)
                    ->where('statut', \App\Models\Stage::STATUTS['EN_ATTENTE'])
                    ->whereNull('id_tuteur')
                    ->count();

                if ($countEnAttente > 0) {
                    $notifications[] = [
                        'icon' => 'fe-users text-warning',
                        'message' => "Stages à affecter tuteur ({$countEnAttente})",
                        'link' => route('directeur.stages'),
                    ];
                }
            }

            // Tu pourras ajouter ici les cas RH et TUTEUR plus tard
        }
        $view->with([
            'nombreStagesAttente' => $nombreStagesAttente,
            'notifications' => $notifications,
        ]);

    });
    }
}
