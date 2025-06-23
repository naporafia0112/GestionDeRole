<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.CreationUtilisateur.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.CreationUtilisateur.roles.create', compact('permissions'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|unique:roles,name',
        'permissions' => 'array'
    ]);

    $role = Role::create(['name' => $request->name]);

    if ($request->has('permissions')) {
        $role->permissions()->attach($request->permissions);
    }

    return redirect()->route('admin.CreationUtilisateur.roles.index')->with('success', 'Rôle créé avec permissions');
}


    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $permissions = $role->permissions;
        return view('admin.CreationUtilisateur.roles.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
       $permissions = Permission::all()->sortBy('name');
        return view('admin.CreationUtilisateur.roles.edit', compact('role', 'permissions'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update($validated);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.CreationUtilisateur.roles.index')
                         ->with('success', 'Rôle mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.CreationUtilisateur.roles.index')->with('success', 'Utilisateur supprimé');
    }
}
