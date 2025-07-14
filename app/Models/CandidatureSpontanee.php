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

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
