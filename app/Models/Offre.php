<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offre extends Model
{
    use SoftDeletes;
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }
}
