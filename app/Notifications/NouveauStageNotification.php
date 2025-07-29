<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NouveauStageNotification extends Notification
{
    use Queueable;

    public $stage;

    public function __construct($stage)
    {
        $this->stage = $stage->load(['candidature.candidat', 'candidatureSpontanee.candidat']);
    }


   public function via($notifiable)
    {
        return ['mail', 'database'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouveau stage dans votre département')
            ->greeting('Bonjour Directeur,')
            ->line('Un nouveau stage a été ajouté dans votre département.')
            ->line('Détails du stage :')
            ->line('Titre : ' . $this->stage->sujet)
            ->action('Voir le stage', url('/stages/' . $this->stage->id))
            ->line('Merci de votre collaboration.');
    }
  
    public function toArray($notifiable)
    {
        // Chercher le candidat via candidature ou candidatureSpontanee
        $candidat = null;

        if ($this->stage->candidature && $this->stage->candidature->candidat) {
            $candidat = $this->stage->candidature->candidat;
        } elseif ($this->stage->candidatureSpontanee && $this->stage->candidatureSpontanee->candidat) {
            $candidat = $this->stage->candidatureSpontanee->candidat;
        }

        $nomComplet = $candidat ? trim($candidat->nom . ' ' . $candidat->prenoms) : 'Non renseigné';

        return [
            'title' => 'Nouveau stage',
            'message' => 'Un nouveau stage a été ajouté dans votre département.',
            'icon' => 'fe-briefcase',
            'bg' => 'bg-success',
            'time' => now()->diffForHumans(),
            'sujet' => $this->stage->sujet,
            'etudiant' => $nomComplet,
            'stage_id' => $this->stage->id, 
        ];
    }

}
