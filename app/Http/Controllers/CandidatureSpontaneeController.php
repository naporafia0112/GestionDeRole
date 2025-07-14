<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CandidatureSpontanee;
use App\Models\Candidat;
use Illuminate\Support\Facades\Storage;

class CandidatureSpontaneeController extends Controller
{
    public function index()
    {
        $candidatures = CandidatureSpontanee::with('candidat')->latest()->paginate(10);
        return view('admin.candidatures.spontanee.candidatures-spontanees', compact('candidatures'));
    }
    public function create()
    {
        return view('vitrine.candidature-spontanee');
    }

     public function show($id)
    {
        $candidature = CandidatureSpontanee::with('candidat')->findOrFail($id);

        // Numéro d'ordre dans la liste
        $toutesCandidatures = CandidatureSpontanee::orderBy('id')->pluck('id')->toArray();
        $numero = array_search($candidature->id, $toutesCandidatures) + 1;

        $statut = $candidature->statut;

        return view('admin.candidatures.spontanee.show', compact('candidature', 'numero', 'statut'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:30',
            'quartier' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'type_depot' => 'required|in:stage professionnel,stage académique,stage de préembauche',
            'cv_fichier' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'lm_fichier' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'lr_fichier' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'message' => 'nullable|string',
        ]);

        // Enregistre ou récupère le candidat
        $candidat = Candidat::firstOrCreate(
            ['email' => $validated['email']],
            [
                'nom' => $validated['nom'],
                'prenoms' => $validated['prenoms'],
                'telephone' => $validated['telephone'],
                'quartier' => $validated['quartier'],
                'ville' => $validated['ville'],
                'type_depot' => $validated['type_depot'],
            ]
        );

        // Upload fichiers
        $cv = $request->file('cv_fichier')?->store('cvs', 'public');
        $lm = $request->file('lm_fichier')?->store('lms', 'public');
        $lr = $request->file('lr_fichier')?->store('lrs', 'public');

        CandidatureSpontanee::create([
            'candidat_id' => $candidat->id,
            'cv_fichier' => $cv,
            'lm_fichier' => $lm,
            'lr_fichier' => $lr,
            'message' => $validated['message'],
        ]);

        return redirect()->back()->with('success', 'Candidature envoyée avec succès.');
    }
}
