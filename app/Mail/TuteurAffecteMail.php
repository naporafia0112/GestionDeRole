<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TuteurAffecteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tuteur;
    public $candidature;

    public function __construct($tuteur, $candidature)
    {
        $this->tuteur = $tuteur;
        $this->candidature = $candidature;
    }

    public function build()
    {
        return $this->subject('Nouvelle Affectation de Stage')
                    ->view('emails.tuteur_affecte');
    }
}
