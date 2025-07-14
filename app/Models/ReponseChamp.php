<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReponseChamp extends Model
{
    protected $table = 'reponses_champs';
    protected $fillable = ['reponse_formulaire_id', 'champ_formulaire_id', 'valeur'];

    public function reponseFormulaire()
    {
        return $this->belongsTo(ReponseFormulaire::class);
    }

    public function champFormulaire()
    {
        return $this->belongsTo(ChampFormulaire::class);
    }
}
