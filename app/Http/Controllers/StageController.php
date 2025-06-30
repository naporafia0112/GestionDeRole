<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stage;
use App\Models\User;
use App\Models\Candidat;
class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stages = Stage::with(['candidat', 'tuteur'])->latest()->get();
        return view('admin.stages.index', compact('stages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tuteurs = User::whereHas('roles', function($q) {
        $q->where('name', 'tuteur');
        })->get();

        $candidats = Candidat::all();

        return view('admin.stages.create', compact('tuteurs', 'candidats'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'id_candidat' => 'required|exists:candidats,id',
            'id_tuteur' => 'required|exists:users,id',
            'sujet' => 'required|string',
            'lieu' => 'required|string',
            'departement' => 'required|string',
            // Optionnel si tu veux laisser le choix dans le formulaire
            'statut' => 'nullable|string',
        ]);

        $data = $request->all();

        // Si aucun statut fourni, on le définit par défaut
        if (empty($data['statut'])) {
            $data['statut'] = 'en_attente';
        }

        Stage::create($data);

        return redirect()->route('stages.index')->with('success', 'Stage créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
