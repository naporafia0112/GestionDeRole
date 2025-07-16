<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('admin.CreationUtilisateur.roles.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.CreationUtilisateur.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Permission créée avec succès');
    }

    public function edit(Permission $permission)
    {
        return view('admin.CreationUtilisateur.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Permission mise à jour avec succès');
    }

    public function destroy(Permission $permission)
    {
        $permission->roles()->detach(); // détacher les relations
        $permission->delete();

        return redirect()->route('roles.index')->with('success', 'Permission supprimée');
    }
}
