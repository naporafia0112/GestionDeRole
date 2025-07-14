<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_tuteur',
        'id_candidature',
        'date_debut',
        'date_fin',
        'remuneration',
        'sujet',
        'lieu',
        'statut',
        'id_departement',
        'note_finale',
        'validation_directeur',
        'rapport_stage_fichier',
    ];

    const STATUTS = [
        'EN_ATTENTE' => 'en_attente',
        'EN_COURS'   => 'en_cours',
        'TERMINE'    => 'Termine',
        'ANNULE'     => 'annule',
    ];
    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];


    /**
     * Candidat lié au stage
     */
    public function candidature()
    {
        return $this->belongsTo(Candidature::class, 'id_candidature');
    }
/**public function departement()
{
    return $this->belongsTo(Departement::class, 'id_departement');
}+**/

    /**
     * Offre liée au stage via la candidature
     * Utilise hasOneThrough pour accéder à l'offre à travers la candidature
     */
    public function offre()
    {
        return $this->hasOneThrough(
            Offre::class,
            Candidature::class,
            'id',            // clé locale dans Candidature
            'id',            // clé locale dans Offre
            'id_candidature',// clé dans Stage
            'id_offre'       // clé dans Candidature
        );
    }

    /**
     * Tuteur lié au stage (User ayant le rôle "tuteur")
     */
    public function tuteur()
    {
        return $this->belongsTo(User::class, 'id_tuteur');
    }
    public function departement()
    {
        return $this->belongsTo(Departement::class, 'id_departement');
    }

    // Stage.php
    public function formulaire()
    {
        return $this->hasOne(Formulaire::class);
    }

}
