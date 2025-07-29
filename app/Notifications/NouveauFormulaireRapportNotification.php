<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NouveauFormulaireRapportNotification extends Notification
{
    use Queueable;

    protected $stage;

    public function __construct($stage)
    {
        $this->stage = $stage;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // email + notification en base
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nouveau formulaire de rapport disponible')
                    ->greeting('Bonjour ' . $notifiable->name)
                    ->line('Un nouveau formulaire de rapport a été créé pour le stage de votre candidat.')
                    ->action('Voir le stage', url(route('tuteur.formulaires.affichage', $this->stage->id)))
                    ->line('Merci de vérifier et remplir ce formulaire dès que possible.');
    }

    public function toArray($notifiable)
    {
        return [
            'stage_id' => $this->stage->id,
            'message' => 'Un nouveau formulaire de rapport a été créé pour votre stage.',
        ];
    }
}
