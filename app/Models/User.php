<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Departement;
use App\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'id_departement',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Toujours charger les rôles avec l'utilisateur
    protected $with = ['roles'];
// app/Models/User.php

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }


    /**
     * Vérifie si l'utilisateur a un ou plusieurs rôles.
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if (is_array($roles)) {
            // On vérifie si au moins un rôle est présent
            return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
        }
        return $this->roles->contains('name', $roles);
    }

    /**
     * Vérifie si l'utilisateur a une permission donnée.
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifie si l'utilisateur a au moins une permission parmi une liste.
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->pluck('name')->intersect($permissions)->isNotEmpty()) {
                return true;
            }
        }
        return false;
    }
    public function departement() {
        return $this->belongsTo(Departement::class, 'id_departement');
    }

    public function direction() {
        return $this->hasOne(Departement::class, 'id_directeur');
    }
    public function formulairesCrees()
    {
        return $this->hasMany(Formulaire::class, 'cree_par');
    }
    public function stages()
    {
        return $this->hasMany(\App\Models\Stage::class, 'id_tuteur');
    }
    
}
