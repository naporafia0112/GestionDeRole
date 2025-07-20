<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entretien extends Model
{
    use HasFactory;

    public const TYPES = [
        'présentiel' => 'Présentiel',
        'en_ligne'   => 'En ligne',
    ];

    // Statuts d’entretien : clé => label
    public const STATUTS = [
        'prevu'     => 'Prévu',
        'en_cours'  => 'En cours',
        'effectuee' => 'Effectué',
        'annule'    => 'Annulé',
    ];


    protected $fillable = [
        'date',
        'date_debut',
        'date_fin',
        'heure',
        'lieu',
        'type',
        'statut',
        'commentaire',
        'id_candidat',
        'id_offre',
    ];
    // App\Models\Entretien.php
    protected $casts = [
        'date' => 'date',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];


    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'id_candidat');
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class, 'id_offre');
    }

    public function getDureeAttribute()
    {
        if ($this->date_debut && $this->date_fin) {
            return $this->date_debut->diffInMinutes($this->date_fin);
        }
        return null;
    }

    public function scopeAVenir($query)
    {
        return $query->where('statut', 'prévu')
                    ->where('date', '>=', now()->toDateString());
    }
    public function getStatutAutomatiqueAttribute()
    {
        $now = now();

        if ($this->statut === 'annulé') {
            return 'annulé';
        }

        if ($this->date_debut && $this->date_fin) {
            if ($now->lt($this->date_debut)) {
                return 'prévu';
            } elseif ($now->between($this->date_debut, $this->date_fin)) {
                return 'en_cours';
            } elseif ($now->gt($this->date_fin)) {
                return 'effectuée';
            }
        }

        return $this->statut; // fallback
    }

}
