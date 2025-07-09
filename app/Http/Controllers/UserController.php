<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.CreationUtilisateur.user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $departements = Departement::all();
        return view('admin.CreationUtilisateur.user.create', compact('roles', 'departements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|confirmed|min:6',
            'role_id'         => 'required|exists:roles,id',
            'id_departement'  => 'nullable|exists:departements,id',
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'id_departement' => $request->id_departement,
        ]);

        $user->roles()->sync([$request->role_id]);

        return redirect()->route('user.index')->with('success', 'Utilisateur ajouté avec succès !');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $departements = Departement::all();
        return view('admin.CreationUtilisateur.user.edit', compact('user', 'roles', 'departements'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'            => 'required',
            'email'           => "required|email|unique:users,email,{$user->id}",
            'roles'           => 'required|array',
            'roles.*'         => 'exists:roles,id',
            'id_departement'  => 'nullable|exists:departements,id',
        ]);

        $user->update([
            'name'           => $request->name,
            'email'          => $request->email,
            'id_departement' => $request->id_departement,
        ]);

        $user->roles()->sync($request->roles);

        return redirect()->route('user.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Utilisateur supprimé');
    }

    public function show(User $user)
    {
        $roles = $user->roles;
        $permissions = [];

        foreach ($roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission->name;
            }
        }

        $permissions = array_unique($permissions);

        return view('admin.CreationUtilisateur.user.show', compact('user', 'roles', 'permissions'));
    }
}
