<?php

namespace App\Http\Controllers;

use App\Models\Entretien;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
        // Définir une couleur selon le statut
        $color = match (strtolower($e->statut)) {
            'prévu', 'prevu'    => '#0d6efd', // bleu
            'en cours'          => '#ffc107', // jaune
            'effectuee'          => '#198754', // vert foncé
            'termine'           => '#20c997', // vert clair
            'annulé', 'annule'  => '#dc3545', // rouge
            default             => '#6c757d', // gris (statut inconnu)
        };

        return [
            'id'     => $e->id,
            'title'  => $e->type,
            'statut' => $e->statut,
            'start'  => $e->date_debut,
            'end'    => $e->date_fin,
            'color'  => $color,
        ];
    });

    return response()->json($events);
}

    // Formulaire de création d’un entretien
    public function create(Request $request)
    {
        $candidats = Candidat::whereHas('candidatures', function ($query) {
            $query->where('statut', 'retenu');
        })->get();

        // Récupérer les ids passés en GET pour pré-remplissage
        $id_candidat = $request->query('id_candidat');
        $id_offre = $request->query('id_offre');

        $offres = $id_offre ? Offre::where('id', $id_offre)->get() : Offre::all();


        return view('admin.entretiens.create', compact('candidats', 'offres', 'id_candidat', 'id_offre'));
    }



    // Enregistrement d’un entretien avec validation et gestion d'erreurs
    public function store(Request $request)
    {
        $now = Carbon::now(); // heure actuelle
        $entretienDateTime = Carbon::parse($request->date . ' ' . $request->heure);

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'heure' => 'required',
            'lieu' => ['required', 'string', 'min:3', 'max:255', 'regex:/^(?=.*[a-zA-Z])(?=.{3,})(?!.*(.)\1{2,})[a-zA-Z\s\-\'éèàâçùêôîï]+$/u'],
            'statut' => 'nullable|string|in:prevu,en_cours,effectuee,termine,annule',
            'commentaire' => 'nullable|string',
            'id_candidat' => 'required|exists:candidats,id',
            'id_offre' => 'required|exists:offres,id',
        ]);

        $validator->after(function ($validator) use ($entretienDateTime, $now) {
            if ($entretienDateTime->lte($now->copy()->addHour())) {
                $validator->errors()->add('date', 'La date et l\'heure doivent être au moins une heure après l\'heure actuelle.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $debut = $entretienDateTime;
            $fin = $debut->copy()->addHour();

            Entretien::create([
                'date' => $request->date,
                'heure' => $request->heure,
                'lieu' => $request->lieu,
                'type' => $request->type,
                'statut' =>'prévu',
                'commentaire' => $request->commentaire,
                'id_candidat' => $request->id_candidat,
                'id_offre' => $request->id_offre,
                'date_debut' => $debut,
                'date_fin' => $fin,
            ]);

            return redirect()->route('entretiens.calendrier')
                ->with('success', 'Entretien créé avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de l\'entretien : ' . $e->getMessage())
                ->withInput();
        }
    }

    // Liste des entretiens par statut
    public function index()
    {
        $entretiensPrevus = Entretien::where('statut', 'prevu')->orderBy('date')->get();
        $entretiensAnnules = Entretien::where('statut', 'annule')->orderBy('date')->get();
        $entretiensEncours = Entretien::where('statut', 'en_cours')->orderBy('date')->get();
        $entretiensTermines = Entretien::where('statut', 'termine')->orderBy('date')->get();
        $entretiensEffectues = Entretien::where('statut', 'effectuee')->orderBy('date')->get();

        return view('admin.entretiens.index', compact(
            'entretiensPrevus',
            'entretiensAnnules',
            'entretiensEncours',
            'entretiensEffectues',
            'entretiensTermines'
        ));
    }

    // Afficher un entretien spécifique
    public function show($id)
    {
        $entretien = Entretien::with('candidat', 'offre')->findOrFail($id);
        return view('admin.entretiens.show', compact('entretien'));
    }

    // Formulaire édition
    public function edit($id)
    {
        $entretien = Entretien::findOrFail($id);
        $candidats = Candidat::all();
        $offres = Offre::all();
        return view('admin.entretiens.edit', compact('entretien', 'candidats', 'offres'));
    }

    // Mise à jour avec validation, gestion d'erreurs, alertes
    public function update(Request $request, $id)
    {
        $now = Carbon::now();
        $entretienDateTime = Carbon::parse($request->date . ' ' . $request->heure);

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'heure' => 'required',
            'lieu' => ['required', 'string', 'min:3', 'max:255','regex:/^(?=.*[a-zA-Z])(?=.{3,})(?!.*(.)\1{2,})[a-zA-Z\s\-\'éèàâçùêôîï]+$/u'],
            'type' => 'required|string|max:255',
            'statut' => 'required|string|in:prevu,en_cours,effectuee,termine,annule',
            'commentaire' => 'nullable|string',
            'id_candidat' => 'required|exists:candidats,id',
            'id_offre' => 'required|exists:offres,id',
        ]);

        // Règle personnalisée pour date + heure > now + 1h
        $validator->after(function ($validator) use ($entretienDateTime, $now) {
            if ($entretienDateTime->lte($now->copy()->addHour())) {
                $validator->errors()->add('date', 'La date et l\'heure doivent être au moins une heure après l\'heure actuelle.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $entretien = Entretien::findOrFail($id);

            $debut = $entretienDateTime;
            $fin = $debut->copy()->addHour();

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

            return redirect()->route('entretiens.calendrier')
                ->with('success', 'Entretien modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    // Suppression avec gestion d’erreurs et alertes
    public function destroy($id)
    {
        try {
            Entretien::destroy($id);
            return redirect()->route('entretiens.calendrier')
                ->with('success', 'Entretien supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('entretiens.calendrier')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    // Annulation avec alertes
    public function annuler($id)
    {
        try {
            $entretien = Entretien::findOrFail($id);
            $entretien->statut = 'annule';
            $entretien->save();

            return back()->with('success', 'Entretien annulé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    // Retour JSON pour affichage modal / ajax (si besoin)
   public function showJson($id)
    {
   $entretien = Entretien::with(['candidat', 'offre'])->findOrFail($id);
        return response()->json([
            'title' => $entretien->type,
            'date' => \Carbon\Carbon::parse($entretien->date)->format('d/m/Y'),
            'heure' => $entretien->heure,
            'lieu' => $entretien->lieu,
            'type' => $entretien->type,
            'statut' => $entretien->statut,
            'commentaire' => $entretien->commentaire,
            'candidat' => $entretien->candidat ? $entretien->candidat->nom . ' ' . $entretien->candidat->prenom : '',
            'offre' => $entretien->offre ? $entretien->offre->titre : '',
            'offre_id' => $entretien->offre ? $entretien->offre->id : null, // <-- ajouté ici
        ]);
    }

    // Actions ajax pour FullCalendar (add, update, delete)
    public function action(Request $request)
    {
        if ($request->ajax()) {
            try {
                if ($request->type === 'add') {
                    $entretien = Entretien::create([
                        'type'       => $request->title,
                        'date_debut' => $request->start,
                        'date_fin'   => $request->end,
                        'lieu'       => $request->lieu ?? 'Lieu non défini',
                        'heure'      => date('H:i:s', strtotime($request->start)),
                        'statut'     => $request->statut ?? 'prevu',
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
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }
}
