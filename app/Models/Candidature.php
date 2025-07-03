<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'offre_id',
        'candidat_id',
        'date_soumission',
        'statut',
        'cv_fichier',
        'lm_fichier',
        'lr_fichier',
        'uuid',
        'score',
        'commentaire',
    ];

    public const STATUTS = [
        'en_cours'   => 'En cours de traitement',
        'retenu'     => 'Retenu',
        'valide'     => 'Validé',
        'rejete'     => 'Rejeté',
    ];

    protected $casts = [
        'date_soumission' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($candidature) {
            $candidature->uuid = Str::uuid()->toString();
        });
    }

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }
    public function entretien()
    {
        return $this->hasOne(Entretien::class, 'id_candidat', 'candidat_id')
                    ->where('id_offre', $this->offre_id);
    }

}
