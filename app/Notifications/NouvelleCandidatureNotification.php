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
            'titre' => 'Nouvelle candidature',
            'message' => "Une nouvelle candidature a été soumise.",
            'link' => route('candidatures.show', $this->candidature->id),
            'icon' => 'mdi mdi-account-plus',
            'bg' => 'bg-info',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
