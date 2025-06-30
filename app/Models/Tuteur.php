<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tuteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'fonction',
        'service_id',
    ];

    // Relations


    public function stagiaires()
    {
        return $this->hasMany(Candidat::class);
    }
}
