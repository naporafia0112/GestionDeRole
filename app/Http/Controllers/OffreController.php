<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\Localisation;
use Illuminate\Support\Facades\Storage;
class OffreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offres = Offre::all();
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
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'localisation_id' => 'required|exists:localisations,id',
            'date_publication' => 'required|date|after_or_equal:today',
            'exigences' => 'required|string',
            'date_limite' => 'required|date|after:date_publication',
            'statut' => 'nullable|string',
            'departement' => 'required|string',
            'fichier' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        try {
            $offre = new Offre();
            $offre->fill($request->except('fichier'));
            
            if ($request->hasFile('fichier')) {
                $offre->fichier = $this->storeFile($request->file('fichier'));
            }

            $offre->est_publie = false;
            $offre->save();

            return redirect()->route('offres.index')
                   ->with('success', 'Offre enregistrée avec succès');

        } catch (\Exception $e) {
            return back()->withInput()
                   ->with('error', "Erreur lors de l'enregistrement: ".$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offre $offre)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'localisation_id' => 'required|exists:localisations,id',
            'date_publication' => 'required|date|after_or_equal:today',
            'exigences' => 'required|string',
            'date_limite' => 'required|date|after:date_publication',
            'statut' => 'nullable|string',
            'departement' => 'required|string',
            'fichier' => 'nullable|file|mimes:pdf|max:2048',
            'est_publie' => 'boolean',

        ]);

        try {
            if ($request->hasFile('fichier')) {
                $this->deleteFile($offre->fichier);
                $validated['fichier'] = $this->storeFile($request->file('fichier'));
            }

            $validated['est_publie'] = $request->has('est_publie');
            $offre->update($validated);

            return redirect()->route('offres.index')
                   ->with('success', 'Offre modifiée avec succès');

        } catch (\Exception $e) {
            return back()->withInput()
                   ->with('error', "Erreur lors de la modification: ".$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offre $offre)
    {
        try {
            $this->deleteFile($offre->fichier);
            $offre->delete();

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

