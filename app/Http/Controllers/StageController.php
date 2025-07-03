<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stage;
use App\Models\User;
use App\Models\Candidat;

class StageController extends Controller
{
    /**
     * Affiche la liste des stages.
     */
    public function index()
    {
        $stages = Stage::with(['candidat', 'tuteur'])->latest()->get();
        return view('admin.stages.index', compact('stages'));
    }

    /**
     * Formulaire création stage (RH) - sans tuteur
     */
   public function create(Request $request)
    {
        $id_candidat = $request->get('id_candidat');
        $candidat = null;

        if ($id_candidat) {
            $candidat = Candidat::find($id_candidat);
        }

        return view('admin.stages.create', compact('candidat'));
    }

    /**
     * Enregistre un nouveau stage sans tuteur (RH).
     */
    public function store(Request $request)
    {var_names: 
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'id_candidat' => 'required|exists:candidats,id',
            'sujet' => 'required|string',
            'lieu' => 'required|string',
            'departement' => 'required|string',
            'statut' => 'nullable|string',
        ]);

        $data = $request->all();

        // Pas de tuteur à la création
        unset($data['id_tuteur']);

        if (empty($data['statut'])) {
            $data['statut'] = 'en_attente';
        }

        Stage::create($data);

        return redirect()->route('stages.index')->with('success', 'Stage créé sans tuteur.');
    }

    /**
     * Affiche un stage.
     */
    public function show($id)
    {
        $stage = Stage::with(['candidat', 'tuteur'])->findOrFail($id);
        return view('admin.stages.show', compact('stage'));
    }

    /**
     * Formulaire pour affecter un tuteur (directeur).
     */
    public function showAffecterTuteur($id)
    {
        $stage = Stage::findOrFail($id);

        $tuteurs = User::whereHas('roles', function($q) {
            $q->where('name', 'tuteur');
        })->get();

        return view('admin.stages.affecter_tuteur', compact('stage', 'tuteurs'));
    }

    /**
     * Enregistre l'affectation du tuteur au stage (directeur).
     */
    public function affecterTuteur(Request $request, $id)
    {
        $request->validate([
            'id_tuteur' => 'required|exists:users,id',
        ]);

        $stage = Stage::findOrFail($id);
        $stage->id_tuteur = $request->input('id_tuteur');
        $stage->save();

        return redirect()->route('stages.index')->with('success', 'Tuteur affecté avec succès.');
    }

    /**
     * Formulaire d'édition d'un stage (optionnel).
     */
    public function edit($id)
    {
        $stage = Stage::findOrFail($id);
        $candidats = Candidat::all();

        // Optionnel : récupérer les tuteurs si besoin dans le formulaire d'édition
        $tuteurs = User::whereHas('roles', function($q) {
            $q->where('name', 'tuteur');
        })->get();

        return view('admin.stages.edit', compact('stage', 'candidats', 'tuteurs'));
    }

    /**
     * Met à jour un stage (optionnel).
     */
    public function update(Request $request, $id)
    {
        $stage = Stage::findOrFail($id);

        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'id_candidat' => 'required|exists:candidats,id',
            'id_tuteur' => 'nullable|exists:users,id',
            'sujet' => 'required|string',
            'lieu' => 'required|string',
            'departement' => 'required|string',
            'statut' => 'nullable|string',
        ]);

        $data = $request->all();

        $stage->update($data);

        return redirect()->route('stages.index')->with('success', 'Stage mis à jour avec succès.');
    }

    /**
     * Supprime un stage (optionnel).
     */
    public function destroy($id)
    {
        $stage = Stage::findOrFail($id);
        $stage->delete();

        return redirect()->route('stages.index')->with('success', 'Stage supprimé avec succès.');
    }
}
