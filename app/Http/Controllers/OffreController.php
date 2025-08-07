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
    public function index()
    {
        // Publier automatiquement les offres dont la date est dépassée
        Offre::where('est_publie', false)
            ->where('date_publication', '<=', now())
            ->update([
                'est_publie' => true,
                'statut' => 'publie',
            ]);

        $offres = Offre::with('localisation')->orderByDesc('created_at')->get();
        return view('admin.offres.index', compact('offres'));
    }

    public function create()
    {
        $statuts = Offre::STATUTS;
        $localisations = Localisation::all();
        return view('admin.offres.create', compact('localisations', 'statuts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => ['required', 'string', 'max:255', new TexteLisible()],
            'description' => ['required', 'string', new TexteLisible()],
            'localisation_id' => ['required', 'exists:localisations,id'],
            'date_publication' => ['required', 'date', new DatePublicationValide($request->has('est_publie'))],
            'exigences' => ['required', 'string', new TexteLisible()],
            'date_limite' => 'required|date|after:date_publication',
            'statut' => ['nullable', Rule::in(array_keys(Offre::STATUTS))],
            'departement' => ['required', 'string', new TexteLisible()],
            'fichier' => 'nullable|file|mimes:pdf|max:2048'
        ],[
            'titre.required'           => 'Le titre de l\'offre est obligatoire.',
            'description.required'     => 'La description est obligatoire.',
            'localisation_id.required' => 'La localisation est obligatoire.',
            'localisation_id.exists'   => 'La localisation sélectionnée est invalide.',
            'date_limite.required'     => 'La date limite est obligatoire.',
            'date_limite.date'         => 'La date limite doit être une date valide.',
            'exigences.required'       => 'Les exigences sont obligatoires.',
            'departement.required'     => 'Le département est obligatoire.',
            'fichier.file'             => 'Le fichier doit être un fichier valide.',
            'fichier.mimes'            => 'Le fichier doit être au format PDF.',
            'fichier.max'              => 'Le fichier ne doit pas dépasser 2 Mo.',
            'date_publication.required' => 'La date de publication est obligatoire.',
            'date_publication.date'    => 'La date de publication doit être une date valide.',
            'date_limite.after'        => 'La date limite doit être postérieure à la date de publication.',]
    );

        try {
            $offre = new Offre();
            $offre->fill($request->except('fichier', 'est_publie', 'date_publication', 'statut'));

            if ($request->hasFile('fichier')) {
                $offre->fichier = $this->storeFile($request->file('fichier'));
            }

            if ($request->has('est_publie')) {
                $offre->est_publie = true;
                $offre->date_publication = now();
                $offre->statut = 'publie';
            } else {
                $offre->est_publie = false;
                $offre->date_publication = $request->input('date_publication');
                $offre->statut = 'brouillon';
            }

            $offre->save();

            return redirect()->route('offres.index')
                ->with('success', 'Offre enregistrée avec succès');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', "Erreur lors de l'enregistrement : " . $e->getMessage());
        }
    }

    public function update(Request $request, Offre $offre)
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255', new TexteLisible()],
            'description' => ['required', 'string', new TexteLisible()],
            'localisation_id' => 'required|exists:localisations,id',
            'date_publication' => ['required', 'date', new DatePublicationValide($request->has('est_publie'))],
            'exigences' => ['required', 'string', new TexteLisible()],
            'date_limite' => 'required|date|after:date_publication',
            'statut' => ['nullable', Rule::in(array_keys(Offre::STATUTS))],
            'departement' => ['required', 'string', new TexteLisible()],
            'fichier' => 'nullable|file|mimes:pdf|max:2048',
            'est_publie' => 'sometimes|boolean',
        ], [
            'titre.required'           => 'Le titre de l\'offre est obligatoire.',
            'description.required'     => 'La description est obligatoire.',
            'localisation_id.required' => 'La localisation est obligatoire.',
            'localisation_id.exists'   => 'La localisation sélectionnée est invalide.',
            'date_limite.required'     => 'La date limite est obligatoire.',
            'date_limite.date'         => 'La date limite doit être une date valide.',
            'exigences.required'       => 'Les exigences sont obligatoires.',
            'departement.required'     => 'Le département est obligatoire.',
            'fichier.file'             => 'Le fichier doit être un fichier valide.',
            'fichier.mimes'            => 'Le fichier doit être au format PDF.',
            'fichier.max'              => 'Le fichier ne doit pas dépasser 2 Mo.',
            'date_publication.required' => 'La date de publication est obligatoire.',
            'date_publication.date'    => 'La date de publication doit être une date valide.',
            'date_limite.after'        => 'La date limite doit être postérieure à la date de publication.',]
    );

        try {
            if ($request->hasFile('fichier')) {
                $this->deleteFile($offre->fichier);
                $validated['fichier'] = $this->storeFile($request->file('fichier'));
            }

            // On écrase les champs manuellement
            if ($request->has('est_publie')) {
                $validated['est_publie'] = true;
                $validated['date_publication'] = now();
                $validated['statut'] = 'publie';
            } else {
                $validated['est_publie'] = false;
                $validated['date_publication'] = $request->input('date_publication');
                $validated['statut'] = 'brouillon';
            }

            $offre->update($validated);

            return redirect()->route('offres.index')
                ->with('success', 'Offre modifiée avec succès');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', "Erreur lors de la modification : " . $e->getMessage());
        }
    }

    public function destroy(Offre $offre)
    {
        try {
            $offre->delete(); // soft delete
            return redirect()->route('offres.index')
                ->with('success', 'Offre supprimée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', "Erreur lors de la suppression: " . $e->getMessage());
        }
    }

    public function show(Offre $offre)
    {
        // Récupérer toutes les offres publiées, triées par date
        $offres = Offre::orderBy('created_at')->get();

        // Trouver la position (index) de cette offre dans la liste
        $numero = $offres->search(function($item) use ($offre) {
            return $item->id === $offre->id;
        }) + 1; // +1 car index commence à 0

        return view('admin.offres.show', compact('offre', 'numero'));
    }

    public function edit(Offre $offre)
    {
        $statuts = Offre::STATUTS;
        $localisations = Localisation::all();
        return view('admin.offres.edit', compact('offre', 'localisations', 'statuts'));
    }


    public function publish(Offre $offre)
    {
        try {
            $offre->update([
                'est_publie' => true,
                'date_publication' => now(),
                'statut' => 'publie',
            ]);

            return back()->with('success', 'Offre publiée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', "Erreur de publication: " . $e->getMessage());
        }
    }

    private function storeFile($file)
    {
        return $file->store('offres', 'public');
    }

    private function deleteFile($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
