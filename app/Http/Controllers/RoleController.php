<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    /**
     * Affiche la liste des rôles.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get(); // Précharge les permissions
        $permissions = Permission::orderBy('name')->get();
        return view('admin.CreationUtilisateur.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau rôle.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('admin.CreationUtilisateur.roles.create', compact('permissions'));
    }

    /**
     * Enregistre un nouveau rôle avec ses permissions.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('roles.index')->with('success', 'Rôle créé avec succès');
    }

    /**
     * Affiche les détails d’un rôle.
     */
    public function show(Role $role)
    {
        $permissions = $role->permissions()->get();
        return view('admin.CreationUtilisateur.roles.show', compact('role', 'permissions'));
    }

    /**
     * Affiche le formulaire d'édition du rôle.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions()->pluck('permissions.id')->toArray();

        return view('admin.CreationUtilisateur.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Met à jour un rôle existant.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'Rôle mis à jour avec succès');
    }

    /**
     * Supprime un rôle.
     */
    public function destroy(Role $role)
    {
        $role->permissions()->detach(); // Important : détacher d’abord les permissions
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rôle supprimé avec succès');
    }
}
