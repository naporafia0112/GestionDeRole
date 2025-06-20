<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\Localisation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Rules\DatePublicationValide;
use App\Rules\TexteLisible;
class OffreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offres = Offre::with('localisation')->orderByDesc('created_at')->paginate(5);
        return view('offres.index', compact('offres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuts = Offre::STATUTS;
        $localisations = Localisation::all();
        return view('offres.create',compact('localisations', 'statuts'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
{
    $request->validate([
        'titre' => ['required', 'string','max:255',new TexteLisible()],
        'description' => ['required', 'string',new TexteLisible()],
        'localisation_id' => 'required|exists:localisations,id',
        'date_publication' => ['required', 'date', new DatePublicationValide($request->has('est_publie'))],
        'exigences' => ['required', 'string',new TexteLisible()],
        'date_limite' => 'required|date|after:date_publication',
        'statut' => 'nullable|string',
        'departement' => ['required', 'string',new TexteLisible()],
        'fichier' => 'nullable|file|mimes:pdf|max:2048'
    ]);

    try {
        $offre = new Offre();
        $offre->fill($request->except('fichier', 'est_publie', 'date_publication'));

        if ($request->hasFile('fichier')) {
            $offre->fichier = $this->storeFile($request->file('fichier'));
        }

        if ($request->has('est_publie')) {
            $offre->est_publie = true;
            $offre->date_publication = now();
        } else {
            $offre->est_publie = false;
            $offre->date_publication = $request->input('date_publication');
        }

        $offre->save();

        return redirect()->route('offres.index')
            ->with('success', 'Offre enregistrée avec succès');

    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', "Erreur lors de l'enregistrement : " . $e->getMessage());
    }
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offre $offre)
{
    $validated = $request->validate([
        'titre' => ['required', 'string','max:255',new TexteLisible()],
        'description' => ['required', 'string',new TexteLisible()],
        'localisation_id' => 'required|exists:localisations,id',
        'date_publication' => ['required', 'date', new DatePublicationValide($request->has('est_publie'))],
        'exigences' => ['required', 'string',new TexteLisible()],
        'date_limite' => 'required|date|after:date_publication',
        'statut' => 'nullable|string',
        'departement' => ['required', 'string',new TexteLisible()],
        'fichier' => 'nullable|file|mimes:pdf|max:2048',
        'est_publie' => 'sometimes|boolean',
    ]);

    try {
        // Gestion du fichier PDF
        if ($request->hasFile('fichier')) {
            $this->deleteFile($offre->fichier);
            $validated['fichier'] = $this->storeFile($request->file('fichier'));
        }

        // Checkbox "est_publie" : si cochée elle arrive dans la requête, sinon non
        $validated['est_publie'] = $request->has('est_publie');

        $offre->update($validated);

        return redirect()->route('offres.index')
            ->with('success', 'Offre modifiée avec succès');
    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', "Erreur lors de la modification : " . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offre $offre)
    {
        try {
            $offre->delete(); // soft delete
            return redirect()->route('offres.index')
                ->with('success', 'Offre supprimée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', "Erreur lors de la suppression: ".$e->getMessage());
        }
    }


    /**
     * Helper method to store file
     */
    private function storeFile($file)
    {
        return $file->store('offres', 'public');
    }

    /**
     * Helper method to delete file
     */
    private function deleteFile($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Offre $offre)
    {
        return view('offres.show', compact('offre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offre $offre)
    {
        $statuts = Offre::STATUTS;
        $localisations = Localisation::all();
        return view('offres.edit',compact('offre', 'localisations', 'statuts'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function publish(Offre $offre)
    {
        try {
        $offre->update([
            'est_publie' => true,
            'date_publication' => now()
        ]);

        return back()->with('success', 'Offre publiée avec succès');

    } catch (\Exception $e) {
        return back()->with('error', "Erreur de publication: ".$e->getMessage());
    }

    }

}

