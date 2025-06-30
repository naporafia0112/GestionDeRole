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
    public function tuteur()
    {
        return $this->belongsTo(Tuteur::class);
    }
}
