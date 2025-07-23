<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NouveauStageNotification extends Notification
{
    public $stage;

    public function __construct($stage)
    {
        $this->stage = $stage;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouveau stage dans votre département')
            ->greeting('Bonjour Directeur,')
            ->line('Un nouveau stage a été ajouté dans votre département.')
            ->line('Détails du stage :')
            ->line('Titre : ' . $this->stage->titre)
            ->line('Déposé par : ' . $this->stage->user->name)
            ->action('Voir le stage', url('/stages/' . $this->stage->id))
            ->line('Merci de votre collaboration.');
    }
}
