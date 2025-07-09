<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model {
    protected $fillable = ['nom', 'description', 'id_directeur'];

    public function directeur() {
        return $this->belongsTo(User::class, 'id_directeur');
    }

    public function tuteurs() {
        return $this->hasMany(User::class, 'id_departement')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'TUTEUR');
            });
    }

    public function stages() {
        return $this->hasMany(Stage::class, 'id_departement');
    }
}
