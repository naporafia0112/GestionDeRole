<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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
        'uuid',
    ];
    public const STATUTS = [
        'reçue'   => 'Reçue',
        'retenu'   => 'Retenu',
        'valide'   => 'Validé',
        'rejete'   => 'Rejeté',
    ];
    protected $casts = [
        'date_soumission' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
    public function getRouteKeyName()
    {
        return 'uuid';
    }
    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
    public function entretiens()
    {
        return $this->hasMany(\App\Models\Entretien::class, 'id_candidat', 'candidat_id');
    }
    public function getAUnEntretienEffectueAttribute()
    {
        return $this->entretiens->contains('statut', 'effectuee');
    }
    // App\Models\CandidatureSpontanee.php

    public function stage()
    {
        return $this->hasOne(Stage::class, 'id_candidature_spontanee');
    }

}
