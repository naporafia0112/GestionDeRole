<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CandidatureSpontanee;

class CandidatureSpontaneeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidature;

    public function __construct(CandidatureSpontanee $candidature)
    {
        $this->candidature = $candidature;
    }

    public function build()
    {
        return $this->subject('Nouvelle candidature spontanée')
                    ->view('emails.candidature-spontanee');
    }
}
