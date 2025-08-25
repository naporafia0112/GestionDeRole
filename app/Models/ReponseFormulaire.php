<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReponseFormulaire extends Model
{
        protected $table = 'reponses_formulaires';

    protected $fillable = ['formulaire_id', 'user_id', 'stage_id', 'valide'];

    public function formulaire()
    {
        return $this->belongsTo(Formulaire::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reponsesChamps()
    {
        return $this->hasMany(ReponseChamp::class);
    }
    public function champs()
    {
        return $this->hasMany(ReponseChamp::class);
    }

    public function tuteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

}
