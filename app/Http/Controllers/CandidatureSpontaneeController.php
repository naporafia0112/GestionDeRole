<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CandidatureSpontanee;
use App\Models\Candidat;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidatureSpontaneeMail;
use App\Models\User;
use App\Notifications\NouvelleCandidatureNotification;

class CandidatureSpontaneeController extends Controller
{
    public function index()
    {
        $candidatures = CandidatureSpontanee::with(['candidat', 'entretiens'])->latest()->paginate(10);
        return view('admin.candidatures.spontanee.candidatures-spontanees', compact('candidatures'));
    }

    public function create()
    {
        return view('vitrine.candidature-spontanee');
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

        $cv = $request->file('cv_fichier')?->store('cvs', 'public');
        $lm = $request->file('lm_fichier')?->store('lms', 'public');
        $lr = $request->file('lr_fichier')?->store('lrs', 'public');

        $candidature = CandidatureSpontanee::create([
            'candidat_id' => $candidat->id,
            'cv_fichier' => $cv,
            'lm_fichier' => $lm,
            'lr_fichier' => $lr,
            'message' => $validated['message'],
            'statut' => 'reçue',
        ]);

        $rhs = User::whereHas('roles', function ($q) {
                $q->where('name', 'RH');
            })->get();

        foreach ($rhs as $rh) {
                $rh->notify(new NouvelleCandidatureNotification($candidature));
            }

        Mail::to($candidat->email)->send(new CandidatureSpontaneeMail($candidature));

        return redirect()->route('vitrine.index')->with('success', 'Candidature envoyée avec succès. Un mail de confirmation vous a été envoyé.');
    }

    public function show($id)
    {
        $candidature = CandidatureSpontanee::with('candidat')->findOrFail($id);

        $toutesCandidatures = CandidatureSpontanee::orderBy('id')->pluck('id')->toArray();
        $numero = array_search($candidature->id, $toutesCandidatures) + 1;

        $statut = $candidature->statut;

        return view('admin.candidatures.spontanee.show', compact('candidature', 'numero', 'statut'));
    }

    public function preview($id, $field)
    {
        $candidature = CandidatureSpontanee::findOrFail($id);

        $allowedFields = ['cv_fichier', 'lm_fichier', 'lr_fichier'];

        if (!in_array($field, $allowedFields) || !$candidature->$field) {
            abort(404, 'Fichier non disponible');
        }

        $path = $candidature->$field;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Fichier introuvable');
        }

        return response()->file(storage_path('app/public/' . $path));
    }

    public function download($id, $field)
    {
        $candidature = CandidatureSpontanee::findOrFail($id);

        $allowedFields = ['cv_fichier', 'lm_fichier', 'lr_fichier'];

        if (!in_array($field, $allowedFields) || !$candidature->$field) {
            abort(404, 'Fichier non disponible');
        }

        $path = $candidature->$field;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Fichier introuvable');
        }

        return Storage::disk('public')->download($path);
    }

    // Méthodes de traitement

    public function retenir($id)
    {
        $candidature = CandidatureSpontanee::findOrFail($id);
        $candidature->update(['statut' => 'retenu']);

        return back()->with('success', 'Candidature retenue avec succès.');
    }

    public function rejeter($id)
    {
        $candidature = CandidatureSpontanee::findOrFail($id);
        $candidature->update(['statut' => 'rejete']);

        return back()->with('success', 'Candidature rejetée.');
    }

    public function valider($id)
    {
        $candidature = CandidatureSpontanee::findOrFail($id);
        $candidature->update(['statut' => 'valide']);

        return redirect()->route('stages.create', [
            'candidature_spontanee_id' => $candidature->id
        ])->with('success', 'Candidature validée. Vous pouvez maintenant créer un stage.');
    }

}
