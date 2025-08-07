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
                // Liste des notifications non lues pour tous les rôles
                $unreadNotifications = $user->unreadNotifications;

                // Si le rôle est DIRECTEUR, on ajoute une logique spécifique
                if ($user->hasRole('DIRECTEUR')) {
                    $nombreStagesAttente = Stage::whereNull('id_tuteur')
                        ->where('id_departement', $user->id_departement)
                        ->count();
                }

                // Formatage des notifications
                $notifications = $unreadNotifications->map(function ($notif) use ($user) {
                    return [
                        'id' => $notif->id,
                        'title' => $notif->data['title'] ?? 'Notification',
                        'message' => $notif->data['message'] ?? '',
                        'icon' => $notif->data['icon'] ?? 'mdi mdi-bell-outline',
                        'bg' => $notif->data['bg'] ?? ($user->hasRole('TUTEUR') ? 'bg-success' : 'bg-primary'),
                        'link' => $notif->data['link'] ?? '#',
                        'time' => $notif->created_at->diffForHumans(),
                        'unread' => is_null($notif->read_at),
                        'created_at' => $notif->created_at,
                    ];
                })->toArray();
            }

            // Partage des données avec toutes les vues
            $view->with([
                'nombreStagesAttente' => $nombreStagesAttente,
                'notifications' => $notifications,
            ]);
        });
    }
}
