<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StageValideNotification extends Notification
{
    use Queueable;

    protected $stage;

    public function __construct($stage)
    {
        $this->stage = $stage;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'titre' => 'Stage validé',
            'message' => "Le stage « {$this->stage->sujet} » a été validé par le directeur.",
            'link' => route('rh.stages.en_cours'),
            'icon' => 'mdi mdi-check-circle',
            'bg' => 'bg-success',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
