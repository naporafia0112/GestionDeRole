<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Formulaire;

class NouveauFormulaireRapportNotification extends Notification
{
    use Queueable;

    protected $formulaire;

    public function __construct(Formulaire $formulaire)
    {
        $this->formulaire = $formulaire;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouveau formulaire de rapport disponible')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Un nouveau formulaire de rapport a été créé pour votre département.')
            ->action('Voir les formulaires', route('tuteur.formulaires.affichage'))
            ->line('Merci de remplir les formulaires correspondants dès que possible.');
    }

    public function toArray($notifiable)
    {
        return [
            'formulaire_id' => $this->formulaire->id,
            'titre' => $this->formulaire->titre,
            'message' => 'Un nouveau formulaire de rapport a été créé.',
        ];
    }
}

