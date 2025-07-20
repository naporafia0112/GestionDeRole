<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\User;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    /**
     * Affiche la liste des départements avec leurs directeurs.
     */
    public function index()
    {
        $departements = Departement::with('directeur')->latest()->get();

        $directeurs = User::whereHas('roles', function ($query) {
            $query->where('name', 'directeur');
        })->get();

        return view('admin.CreationUtilisateur.departements.liste', compact('departements', 'directeurs'));
    }

    /**
     * Enregistre un nouveau département.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:departements,nom',
            'description'=> 'nullable|string|max:255',
        ]);

        Departement::create($request->only('nom', 'id_directeur', 'description'));

        return redirect()->route('departements.index')->with('success', 'Département ajouté avec succès.');
    }

    /**
     * Met à jour un département.
     */
    public function update(Request $request, Departement $departement)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:departements,nom,' . $departement->id,
            'description'=> 'nullable|string|max:255',
        ]);

        $departement->update($request->only('nom', 'id_directeur', 'description'));

        return back()->with('success', 'Département mis à jour avec succès.');
    }

    /**
     * Supprime un département.
     */
    public function destroy(Departement $departement)
    {
        $departement->delete();

        return response()->json(['message' => 'Département supprimé avec succès.']);
    }
}
