<?php

namespace App\Notifications;

use App\Models\ReponseFormulaire;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReponseFormulaireSoumise extends Notification
{
    use Queueable;

    public $reponse;

    public function __construct(ReponseFormulaire $reponse)
    {
        $this->reponse = $reponse->load('formulaire', 'user', 'stage');
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle réponse à votre formulaire')
            ->greeting('Bonjour Directeur,')
            ->line('Un tuteur a rempli un formulaire que vous avez créé.')
            ->line('Titre du formulaire : ' . $this->reponse->formulaire->titre)
            ->line('Tuteur : ' . $this->reponse->user->nom . ' ' . $this->reponse->user->prenoms)
            ->action('Voir la réponse', route('directeur.reponses.details', $this->reponse->id))
            ->line('Merci.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Réponse au formulaire',
            'message' => 'Un tuteur a répondu à votre formulaire.',
            'etudiant' => $this->reponse->user->nom . ' ' . $this->reponse->user->prenoms,
            'formulaire_id' => $this->reponse->formulaire->id,
            'reponse_id' => $this->reponse->id,
        ];
    }
}
