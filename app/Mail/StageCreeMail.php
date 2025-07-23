<?php

namespace App\Mail;

use App\Models\Candidat;
use App\Models\Stage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StageCreeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidat;
    public $stage;

    public function __construct(Candidat $candidat, Stage $stage)
    {
        $this->candidat = $candidat;
        $this->stage = $stage;
    }

    public function build()
    {
        return $this->subject('Nouveau stage créé')
                    ->markdown('emails.stage_cree');
    }
}
