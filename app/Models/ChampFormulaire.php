<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChampFormulaire extends Model
{
    protected $table = 'champs_formulaires';
    protected $fillable = [
        'formulaire_id',
        'label',
        'type',
        'requis',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
        'requis' => 'boolean',
    ];

    public function formulaire()
    {
        return $this->belongsTo(Formulaire::class);
    }
}
