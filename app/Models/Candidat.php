<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenoms',
        'email',
        'telephone',
        'quartier',
        'ville',
        'type_depot',
    ];

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }
    public function candidatureValidee()
    {
        return $this->hasOne(Candidature::class)
                    ->where('statut', 'valide');
    }
    public function candidatureSpontanees()
    {
        return $this->hasMany(CandidatureSpontanee::class, 'candidat_id');
    }

}