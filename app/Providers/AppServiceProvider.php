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
            $user = Auth::user();

            $nombreStagesAttente = 0;
            $notifications = [];

            if ($user) {

                // Notifications et compteurs pour le DIRECTEUR
                if ($user->hasRole('DIRECTEUR')) {
                    // Nombre de stages sans tuteur dans son département
                    $nombreStagesAttente = Stage::whereNull('id_tuteur')
                        ->where('id_departement', $user->id_departement)
                        ->count();

                    // Notifications non lues du directeur
                    $notifications = $user->unreadNotifications->map(function ($notif) {
                        return [
                            'id' => $notif->id,
                            'title' => $notif->data['title'] ?? 'Notification',
                            'message' => $notif->data['message'] ?? '',
                            'icon' => $notif->data['icon'] ?? 'mdi mdi-bell-outline',
                            'bg' => $notif->data['bg'] ?? 'bg-primary',
                            'link' => $notif->data['link'] ?? '#',
                            'time' => $notif->created_at->diffForHumans(),
                            'unread' => is_null($notif->read_at),
                            'created_at' => $notif->created_at,
                        ];
                    })->toArray();
                }

                // Notifications pour le RH
                elseif ($user->hasRole('RH')) {
                    // Par exemple, compter les candidatures en attente
                    // ou toute autre logique spécifique au RH

                    // Exemple : Récupérer toutes ses notifications non lues
                    $notifications = $user->unreadNotifications->map(function ($notif) {
                        return [
                            'id' => $notif->id,
                            'title' => $notif->data['title'] ?? 'Notification',
                            'message' => $notif->data['message'] ?? '',
                            'icon' => $notif->data['icon'] ?? 'mdi mdi-bell-outline',
                            'bg' => $notif->data['bg'] ?? 'bg-primary',
                            'link' => $notif->data['link'] ?? '#',
                            'time' => $notif->created_at->diffForHumans(),
                            'unread' => is_null($notif->read_at),
                            'created_at' => $notif->created_at,
                        ];
                    })->toArray();
                }

                // Autres rôles ou cas (ex: TUTEUR), tu peux compléter ici...
            }

            // Envoie les données à toutes les vues
            $view->with([
                'nombreStagesAttente' => $nombreStagesAttente,
                'notifications' => $notifications,
            ]);
        });
    }
}
