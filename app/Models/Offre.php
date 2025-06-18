<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'date_publication',
        'exigences',
        'date_limite',
        'statut',
        'departement',
        'fichier',
        'localisation_id',
        'est_publie',
    ];

    public const STATUTS = [
    'brouillon' => 'Brouillon',
    'publie' => 'Publié',
    'archive' => 'Archivé',
];

    protected $casts = [
        'date_publication' => 'date',
        'date_limite' => 'date'
    ];

    public function localisation()
{
    return $this->belongsTo(Localisation::class);
}

}
