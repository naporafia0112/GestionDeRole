<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleCandidatureNotification extends Notification
{
    use Queueable;

    protected $candidature;

    public function __construct($candidature)
    {
        $this->candidature = $candidature;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nouvelle candidature',
            'message' => "Une nouvelle candidature a été soumise pour l'offre : " . $this->candidature->offre->titre,
            'link' => route('offres.candidatures', $this->candidature->offre_id),
            'icon' => 'mdi mdi-account-plus',
            'bg' => 'bg-info',
            'created_at' => now()->toDateTimeString(),
        ];
    }

}
