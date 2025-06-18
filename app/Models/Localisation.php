<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localisation extends Model
{
    use HasFactory;
     protected $fillable = ['pays']; // autorise l'ajout en masse

    public function offres()
    {
        return $this->hasMany(Offre::class);
    }
}
