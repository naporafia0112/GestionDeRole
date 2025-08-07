<?php

namespace App\Mail;

use App\Models\Entretien;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EntretienAnnuleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $entretien;

    /**
     * Create a new message instance.
     */
    public function __construct(Entretien $entretien)
    {
        $this->entretien = $entretien;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Votre entretien a été annulé')
                    ->view('emails.annule')
                    ->with([
                        'entretien' => $this->entretien,
                        'candidat' => $this->entretien->candidat,
                    ]);
    }
}
