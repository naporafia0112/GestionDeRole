<?php

namespace App\Http\Controllers;

use App\Models\Entretien;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntretienController extends Controller
{
    // Affiche la vue calendrier (avec FullCalendar)
    public function calendrier()
    {
        return view('admin.entretiens.calendrier');
    }

    // Fournit les événements JSON pour FullCalendar (ajax)
    public function getEvents(Request $request)
    {
        $entretiens = Entretien::all();

        $events = $entretiens->map(function ($e) {
            return [
                'id'    => $e->id,
                'title' => $e->type,
                'statut'=> $e->statut,
                'start' => $e->date_debut,
                'end'   => $e->date_fin,
            ];
        });

        return response()->json($events);
    }

    // Formulaire de création d’un entretien
    public function create()
    {
        $candidats = Candidat::all();
        $offres = Offre::all();

        return view('admin.entretiens.create', compact('candidats', 'offres'));
    }

    // Enregistrement d’un entretien
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
            'heure' => 'required',
            'lieu' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'statut' => 'nullable|string|in:prévu,en_cours,effectuée,annulé',
            'commentaire' => 'nullable|string',
            'id_candidat' => 'required|exists:candidats,id',
            'id_offre' => 'required|exists:offres,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $debut = $request->date . ' ' . $request->heure;
        $fin = date('Y-m-d H:i:s', strtotime($debut) + 3600);

        Entretien::create([
            'date' => $request->date,
            'heure' => $request->heure,
            'lieu' => $request->lieu,
            'type' => $request->type,
            'statut' => $request->statut ?? 'prévu',
            'commentaire' => $request->commentaire,
            'id_candidat' => $request->id_candidat,
            'id_offre' => $request->id_offre,
            'date_debut' => $debut,
            'date_fin' => $fin,
        ]);

        return redirect()->route('entretiens.calendrier')
                         ->with('success', 'Entretien créé avec succès !');
    }

    public function index()
    {
        // Récupérer les entretiens par statut
        $entretiensPrevus = Entretien::where('statut', 'prevu')->orderBy('date')->get();
        $entretiensAnnules = Entretien::where('statut', 'annule')->orderBy('date')->get();
        $entretiensEncours = Entretien::where('statut', 'en_cours')->orderBy('date')->get();
        $entretiensTermines = Entretien::where('statut', 'termine')->orderBy('date')->get();

        return view('admin.entretiens.index', compact(
            'entretiensPrevus',
            'entretiensAnnules',
            'entretiensEncours',
            'entretiensTermines'
        ));
    }

    // Afficher un entretien spécifique

    public function show($id)
    {
        $entretien = Entretien::with('candidat', 'offre')->findOrFail($id);
        return view('entretiens.show', compact('entretien'));
    }

    // Actions ajax pour FullCalendar (add, update, delete)
    public function action(Request $request)
    {
        if ($request->ajax()) {
            if ($request->type === 'add') {
                $entretien = Entretien::create([
                    'type'       => $request->title,
                    'date_debut' => $request->start,
                    'date_fin'   => $request->end,
                    'lieu'       => $request->lieu ?? 'Lieu non défini',
                    'heure'      => date('H:i:s', strtotime($request->start)),
                    'statut'     => $request->statut ?? 'prévu',
                    'commentaire'=> $request->commentaire ?? null,
                    'id_candidat'=> $request->id_candidat ?? null,
                    'id_offre'   => $request->id_offre ?? null,
                ]);
                return response()->json($entretien);
            }

            if ($request->type === 'update') {
                $entretien = Entretien::find($request->id);
                if ($entretien) {
                    $entretien->update([
                        'type'       => $request->title,
                        'date_debut' => $request->start,
                        'date_fin'   => $request->end,
                        'lieu'       => $request->lieu ?? $entretien->lieu,
                        'heure'      => date('H:i:s', strtotime($request->start)),
                        'statut'     => $request->statut ?? $entretien->statut,
                        'commentaire'=> $request->commentaire ?? $entretien->commentaire,
                        'id_candidat'=> $request->id_candidat ?? $entretien->id_candidat,
                        'id_offre'   => $request->id_offre ?? $entretien->id_offre,
                    ]);
                }
                return response()->json($entretien);
            }

            if ($request->type === 'delete') {
                $entretien = Entretien::find($request->id);
                if ($entretien) {
                    $entretien->delete();
                }
                return response()->json(['success' => true]);
            }
        }
    }

    public function edit($id)
    {
        $entretien = Entretien::findOrFail($id);
        $candidats = Candidat::all();
        $offres = Offre::all();
        return view('admin.entretiens.edit', compact('entretien', 'candidats', 'offres'));
    }
public function update(Request $request, $id)
{
    // Validation complète, y compris le champ statut
    $request->validate([
        'date' => 'required|date|after_or_equal:today',
        'heure' => 'required',
        'lieu' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'statut' => 'required|string|in:prévu,en_cours,effectuée,annulé',
        'commentaire' => 'nullable|string',
        'id_candidat' => 'required|exists:candidats,id',
        'id_offre' => 'required|exists:offres,id',
    ]);

    $entretien = Entretien::findOrFail($id);

    // Calcul automatique des dates début et fin (durée = 1h)
    $debut = $request->date . ' ' . $request->heure;
    $fin = date('Y-m-d H:i:s', strtotime($debut) + 3600);

    // Mise à jour avec tous les champs, incluant statut
    $entretien->update([
        'date' => $request->date,
        'heure' => $request->heure,
        'lieu' => $request->lieu,
        'type' => $request->type,
        'statut' => $request->statut,
        'commentaire' => $request->commentaire,
        'id_candidat' => $request->id_candidat,
        'id_offre' => $request->id_offre,
        'date_debut' => $debut,
        'date_fin' => $fin,
    ]);

    return redirect()->route('entretiens.calendrier')->with('success', 'Entretien modifié avec succès.');
}


    public function destroy($id)
    {
        Entretien::destroy($id);
        return redirect()->route('entretiens.calendrier')->with('success', 'Entretien supprimé.');
    }
    public function annuler($id)
    {
        $entretien = Entretien::findOrFail($id);
        $entretien->statut = 'annule';
        $entretien->save();
        return back()->with('success', 'Entretien annulé.');
    }

    public function showJson($id)
{
    $entretien = Entretien::findOrFail($id);

    return response()->json([
        'title' => $entretien->title, // ou autre champ titre
        'date' => $entretien->date->format('d/m/Y'),
        'heure' => $entretien->heure,
        'lieu' => $entretien->lieu,
        'type' => $entretien->type,
        'statut' => $entretien->statut,
        'commentaire' => $entretien->commentaire,
    ]);
}



}
