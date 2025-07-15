<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatureSpontanee extends Model
{
    protected $table = 'candidatures_spontanees';
    use HasFactory;

    protected $fillable = [
        'candidat_id',
        'date_soumission',
        'statut',
        'cv_fichier',
        'lm_fichier',
        'lr_fichier',
        'message',
    ];
    public const STATUTS = [
        'reçue'   => 'Reçue',
        'retenu'   => 'Retenu',
        'valide'   => 'Validé',
        'rejete'   => 'Rejeté',
    ];
    protected $casts = [
        'date_soumission' => 'datetime',
    ];
    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
    public function entretiens()
    {
        return $this->hasMany(\App\Models\Entretien::class, 'id_candidat', 'candidat_id');
    }
    public function getAUnEntretienEffectueAttribute()
    {
        return $this->entretiens->contains('statut', 'effectuee');
    }

}
