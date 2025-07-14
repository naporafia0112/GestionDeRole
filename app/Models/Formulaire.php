<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulaire extends Model
{
    protected $fillable = ['titre', 'id_departement', 'cree_par','stage_id'];

    public function champs()
    {
        return $this->hasMany(ChampFormulaire::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }
    public function reponses()
    {
        return $this->hasMany(ReponseFormulaire::class);
    }
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
    public function estRempliParTuteur($tuteurId)
    {
        return $this->reponses()->where('user_id', $tuteurId)->exists();
    }


}
