<?php

namespace App\Jobs;

use App\Mail\CandidatureConfirmationMail;
use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnvoyerMailCandidature implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $candidature;

    public function __construct(Candidature $candidature)
    {
        $this->candidature = $candidature;
    }

    public function handle()
    {
        try {
            Mail::to($this->candidature->candidat->email)
                ->send(new CandidatureConfirmationMail($this->candidature));
        } catch (\Exception $e) {
            Log::error('Erreur envoi mail candidature : ' . $e->getMessage());
        }
    }
}
