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
        $statutsFiltres = [
        'prevu' => 'Prévu',
        'en_cours' => 'En cours',
        'effectuee' => 'Effectué',
        'termine' => 'Terminé',
        'annule' => 'Annulé',
    ];

        return view('admin.entretiens.calendrier',compact('statutsFiltres'));
    }

        // Fournit les événements JSON pour FullCalendar (ajax)
    public function getEvents(Request $request)
    {
        $entretiens = Entretien::all();

        // Met à jour les entretiens prévus mais expirés
        Entretien::where('statut', 'prevu')
            ->where('date_fin', '<', Carbon::now())
            ->update(['statut' => 'annule']);

        // Libellés lisibles des statuts
        $libelles = [
            'prevu'      => 'Prévu',
            'en_cours'   => 'En cours',
            'effectuee'  => 'Effectué',
            'termine'    => 'Terminé',
            'annule'     => 'Annulé',
        ];

        // Construction des événements
        $events = $entretiens->map(function ($e) use ($libelles) {
            $color = match (strtolower($e->statut)) {
                'prevu'     => '#0d6efd', // bleu
                'en_cours'  => '#ffc107', // jaune
                'effectuee' => '#198754', // vert foncé
                'termine'   => '#20c997', // vert clair
                'annule'    => '#dc3545', // rouge
                default     => '#6c757d', // gris
            };

            return [
                'id'     => $e->id,
                'title'  => $e->type,
                'statut' => $libelles[$e->statut] ?? ucfirst($e->statut),
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
        $date = $request->query('date');
        $heure = $request->query('heure');

        $offres = $id_offre ? Offre::where('id', $id_offre)->get() : Offre::all();


        return view('admin.entretiens.create', compact('candidats', 'offres', 'id_candidat', 'id_offre','date','heure'));
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
        Entretien::where('statut', 'prevu')
        ->where('date_fin', '<', Carbon::now())
        ->update(['statut' => 'annule']);

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
    public function liste_entretiens_export()
    {

         // Met à jour les entretiens prévus expirés en annulés
        Entretien::where('statut', 'prevu')
            ->where('date_fin', '<', Carbon::now())
            ->update(['statut' => 'annule']);

        // Récupère tous les entretiens, ordonnés par date, avec leurs relations utiles
        $entretiens = Entretien::with(['candidat', 'offre'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        // Envoie la collection à la vue
        return view('admin.entretiens.liste_entretiens', compact('entretiens'));
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

        $statutsFiltres = collect(Entretien::STATUTS)
        ->reject(function ($label, $value) {
            return in_array($value, ['prevu']);
        });

        return view('admin.entretiens.edit', compact('entretien', 'candidats', 'offres', 'statutsFiltres'));
    }


    // Mise à jour avec validation, gestion d'erreurs, alertes
    public function update(Request $request, $id)
    {
        // 1. Chargement explicite de l'entretien
        $entretien = Entretien::findOrFail($id);

        // 2. Combiner date et heure
        $dateInput = $request->input('date');
        $heureInput = $request->input('heure');
        if (strlen($heureInput) === 5) { // format "HH:mm"
            $heureInput .= ':00';
        }
        $entretienDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $dateInput . ' ' . $heureInput);
        $now = Carbon::now();

        // 3. Validation
        $validator = Validator::make($request->all(), [
            'date'   => ['required', 'date'],
            'heure'  => ['required'],
            'statut' => ['required', 'in:prevu,en_cours,effectuee,termine,annule'],
            'lieu'   => ['required_if:type,présentiel', 'string'], // Validation pour le lieu
        ]);

        // Règles personnalisées
        $validator->after(function ($validator) use ($entretienDateTime, $now, $request) {
            if ($request->statut === 'effectuee' && $entretienDateTime->gte($now)) {
                $validator->errors()->add('date', 'Un entretien effectué doit avoir une date antérieure à l\'heure actuelle.');
            }

            if ($request->statut !== 'effectuee' && $entretienDateTime->lte($now->copy()->addHour())) {
                $validator->errors()->add('date', 'Pour un entretien à venir, la date doit être au moins une heure après maintenant.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        // 4. Mise à jour garantie (UPDATE, pas INSERT)
        $updateData = [
            'date' => $request->input('date'),
            'heure' => $request->input('heure'),
            'statut' => $request->input('statut'),
            'lieu' => $request->input('lieu', $entretien->lieu), // Garde l'ancienne valeur si non fournie
        ];

        // 5. Debug avant sauvegarde (optionnel)
        logger()->info('Mise à jour entretien', [
            'id' => $entretien->id,
            'data' => $updateData,
            'exists' => $entretien->exists
        ]);

        $entretien->update($updateData);

        // 6. Redirection
        return redirect()->route('entretiens.index')
                        ->with('success', 'Entretien mis à jour avec succès.');
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
            'date' => \Carbon\Carbon::parse($entretien->date)->format('Y-m-d'),
            'heure' => $entretien->heure,
            'lieu' => $entretien->lieu,
            'type' => $entretien->type,
            'statut' => Entretien::STATUTS[$entretien->statut] ?? $entretien->statut,
            'commentaire' => $entretien->commentaire,
            'candidat' => $entretien->candidat ? $entretien->candidat->nom . ' ' . $entretien->candidat->prenoms : '',
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
    public function slots()
    {
        $debutHeure = 8;
        $finHeure = 18;
        $interval = 30; // minutes
        $jours = 5;

        $slots = [];
        $now = Carbon::now()->addHours(2); // **On ajoute 2h ici**
        $entretienExistants = Entretien::select('date', 'heure')->get();

        for ($i = 0; $i < $jours; $i++) {
            $date = $now->copy()->addDays($i);
            if ($date->isWeekend()) continue;

            for ($h = $debutHeure; $h < $finHeure; $h++) {
                for ($m = 0; $m < 60; $m += $interval) {
                    $heure = sprintf('%02d:%02d', $h, $m);
                    $dateStr = $date->format('Y-m-d');

                    $slotDateTime = Carbon::createFromFormat('Y-m-d H:i', $dateStr . ' ' . $heure);

                    // On filtre pour ne garder que les créneaux à +2h minimum
                    if ($slotDateTime->lt($now)) {
                        continue;
                    }

                    // Vérifie que le créneau n'existe pas déjà
                    $existe = $entretienExistants->contains(function ($e) use ($dateStr, $heure) {
                        return $e->date == $dateStr && $e->heure == $heure;
                    });

                    if (!$existe) {
                        $slots[] = [
                            'date' => $dateStr,
                            'heure' => $heure,
                        ];
                    }
                }
            }
        }

        return response()->json($slots);
    }
    public function showSlotsPage(Request $request)
    {
        $id_candidat = $request->query('id_candidat');
        $id_offre = $request->query('id_offre');

        return view('admin.entretiens.creneaux', compact('id_candidat', 'id_offre'));
    }

}
