<?php

namespace App\Mail;

use App\Models\Candidat;
use App\Models\Entretien;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EntretienCreeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidat;
    public $entretien;

    public function __construct(Candidat $candidat, Entretien $entretien)
    {
        $this->candidat = $candidat;
        $this->entretien = $entretien;
    }

    public function build()
    {
        return $this->subject('Notification d\'entretien programmé')
                    ->view('emails.entretien_cree'); // ta vue mail à créer
    }
}
