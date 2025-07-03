<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_debut',
        'date_fin',
        'id_candidat',
        'id_tuteur',
        'sujet',
        'lieu',
        'statut',
        'departement',
        'note_finale',
    ];

    /**
     * Candidat lié au stage
     */
    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'id_candidat');
    }

    /**
     * Tuteur lié au stage (User ayant le rôle "tuteur")
     */
    public function tuteur()
    {
        return $this->belongsTo(User::class, 'id_tuteur');
    }
    
}
