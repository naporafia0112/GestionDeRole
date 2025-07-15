<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\User;
use App\Models\ReponseFormulaire;
use App\Models\Candidature;
use Illuminate\Http\Request;
use App\Models\Departement;
use Illuminate\Support\Facades\Auth;
use App\Models\Candidat;
use Carbon\Carbon;
use App\Models\CandidatureSpontanee;



class StageController extends Controller
{
    /**
     * Affiche la liste des stages.
     */
       /** */ public function index()
        {
            $stages = Stage::with(['candidature.candidat', 'tuteur'])->latest()->get();
            return view('admin.stages.index', compact('stages'));
        }

    /**
     * Formulaire de création d’un stage (RH).
     */
    public function create(Request $request)
    {
        $departements = Departement::all();
        // Vérifie si paramètre d'URL présent (GET)
        if ($request->filled('id_candidature')) {
            $candidature = Candidature::with('candidat', 'offre')->findOrFail($request->id_candidature);
            return view('admin.stages.create', [
                'candidature' => $candidature,
                'offre' => $candidature->offre,
                'type' => 'classique',
                'departements' => $departements
            ]);
        }

        if ($request->filled('candidature_spontanee_id')) {
            $candidature = CandidatureSpontanee::with('candidat')->findOrFail($request->candidature_spontanee_id);
            return view('admin.stages.create', [
                'candidature' => $candidature,
                'offre' => null,
                'type' => 'spontanee',
                'departements' => $departements
            ]);
        }

        return redirect()->route('dashboard.RH')->with('error', 'Paramètres invalides pour la création du stage.');
    }


    /**
     * Enregistrement d’un nouveau stage.
     */
   public function store(Request $request)
    {
        // 1. Validation des données reçues
        $request->validate([
            'type' => 'required|in:classique,spontanee',
            'id_candidature' => 'required_if:type,classique|exists:candidatures,id',
            'id_candidature_spontanee' => 'required_if:type,spontanee|exists:candidatures_spontanees,id',
            'date_debut' => 'required|date',
            'date_fin' => [
                'required',
                'date',
                'after_or_equal:date_debut',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->has('date_debut')) {
                        $dateDebut = \Carbon\Carbon::parse($request->input('date_debut'));
                        $dateFin = \Carbon\Carbon::parse($value);

                        if ($dateFin->lt($dateDebut->copy()->addMonth())) {
                            $fail('La date de fin doit être au moins 1 mois après la date de début.');
                        }
                    }
                },
            ],
            'sujet' => 'required|string|max:255',
            'lieu' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\',.-]+$/u'],
            'id_departement' => 'required|exists:departements,id',
            'statut' => 'nullable|string|in:' . implode(',', Stage::STATUTS),
            'remuneration' => 'nullable|numeric',
        ]);

        // 2. Récupérer les données communes
        $data = $request->only([
            'date_debut',
            'date_fin',
            'sujet',
            'lieu',
            'id_departement',
            'statut',
            'remuneration',
        ]);

        // 3. Statut par défaut si non fourni
        $data['statut'] = $data['statut'] ?? Stage::STATUTS['EN_ATTENTE'];

        // 4. Selon le type, récupérer la candidature liée et vérifier doublons
        if ($request->type === 'classique') {
            $candidature = Candidature::with(['candidat', 'offre'])->find($request->id_candidature);

            if (!$candidature) {
                return back()->with('error', 'Candidature classique introuvable.');
            }

            // Vérifier doublon : stage actif pour ce candidat et cette offre
            $stageExistant = Stage::whereHas('candidature', function ($query) use ($candidature) {
                $query->where('candidat_id', $candidature->candidat_id)
                    ->where('offre_id', $candidature->offre_id);
            })
            ->whereIn('statut', ['en_cours', 'valide'])
            ->exists();

            if ($stageExistant) {
                return back()->with('error', 'Ce candidat a déjà un stage actif pour cette offre.');
            }

            $data['id_candidature'] = $candidature->id;
            $data['id_candidature_spontanee'] = null;

        } else {
            $candidatureSpontanee = CandidatureSpontanee::with('candidat')->find($request->id_candidature_spontanee);

            if (!$candidatureSpontanee) {
                return back()->with('error', 'Candidature spontanée introuvable.');
            }

            // Vérifier doublon : stage actif pour ce candidat et cette candidature spontanée
            $stageExistant = Stage::where('id_candidature_spontanee', $candidatureSpontanee->id)
                ->whereIn('statut', ['en_cours', 'valide'])
                ->exists();

            if ($stageExistant) {
                return back()->with('error', 'Ce candidat a déjà un stage actif pour cette candidature spontanée.');
            }

            $data['id_candidature_spontanee'] = $candidatureSpontanee->id;
            $data['id_candidature'] = null;
        }

        // 5. Création du stage
        Stage::create($data);

        // 6. Redirection avec message succès
        return redirect()->route('rh.stages.en_cours')->with('success', 'Stage créé avec succès.');
    }

    /**
     *Détail d’un stage.
     */
    public function show(Stage $stage)
    {
        $numero = Stage::where('id', '<=', $stage->id)->count();

        $stage->load([
            'candidature' => function($query) {
                $query->with(['candidat' => function($q) {
                    // Solution alternative pour gérer les candidats supprimés
                    if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(Candidat::class))) {
                        $q->withTrashed();
                    }
                }]);
            },
            'tuteur',
            'departement'
        ]);

        // Solution de repli si la candidature ou le candidat n'existe pas
        $candidat = $stage->candidature->candidat ?? null;

        // Alternative plus explicite:
        // $candidat = $stage->candidature ? ($stage->candidature->candidat ?? null) : null;

        return view('admin.stages.rh.details', [
            'stage' => $stage,
            'numero' => $numero,
            'candidat' => $candidat,
            'departement' => $stage->departement
        ]);
    }

    public function detailstagedirecteur(Stage $stage)
    {
        $numero = Stage::where('id', '<=', $stage->id)->count();

        $stage->load([
            'candidature' => function($query) {
                $query->with(['candidat' => function($q) {
                    if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(Candidat::class))) {
                        $q->withTrashed();
                    }
                }]);
            },
            'tuteur',
            'departement'
        ]);

        $candidat = $stage->candidature->candidat ?? null;

        $cvValide = null;
        if ($candidat) {
            // Récupérer la candidature validée du candidat
            $candidatureValidee = $candidat->candidatures()->where('statut', 'valide')->first();
            if ($candidatureValidee) {
                $cvValide = $candidatureValidee->cv_fichier;
            }
        }

        return view('admin.stages.directeurs.detail_stage', [
            'stage' => $stage,
            'numero' => $numero,
            'candidat' => $candidat,
            'departement' => $stage->departement,
            'cvValide' => $cvValide,
        ]);
    }

    public function detailstagetuteur(Stage $stage)
    {
        $numero = Stage::where('id', '<=', $stage->id)->count();

        $stage->load([
            'candidature' => function($query) {
                $query->with(['candidat' => function($q) {
                    if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(Candidat::class))) {
                        $q->withTrashed();
                    }
                }]);
            },
            'tuteur',
            'departement'
        ]);

        $candidat = $stage->candidature->candidat ?? null;

        $cvValide = null;
        if ($candidat) {
            // Récupérer la candidature validée du candidat
            $candidatureValidee = $candidat->candidatures()->where('statut', 'valide')->first();
            if ($candidatureValidee) {
                $cvValide = $candidatureValidee->cv_fichier;
            }
        }

        return view('admin.stages.tuteur.detail_stage_tuteur', [
            'stage' => $stage,
            'numero' => $numero,
            'candidat' => $candidat,
            'departement' => $stage->departement,
            'cvValide' => $cvValide,
        ]);
    }


    /**
     * Formulaire d’affectation de tuteur.
     */
   public function affecterTuteur(Request $request, $id)
    {
        $stage = Stage::findOrFail($id);

        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403);
        }

        $request->validate([
            'id_tuteur' => 'required|exists:users,id',
        ]);

        $stage->id_tuteur = $request->id_tuteur;
        $stage->statut = Stage::STATUTS['EN_COURS'];
        $stage->save();

        return redirect()->route('stages.en_cours')->with('success', 'Tuteur affecté avec succès.');
    }

    /**
     * Édition d’un stage.
     */
    public function edit($id)
    {
        $stage = Stage::with(['candidature', 'candidature.offre'])->findOrFail($id);

        $tuteurs = User::whereHas('roles', function ($q) {
            $q->where('name', 'TUTEUR');
        })
        ->where('id_departement', $stage->id_departement)
        ->get();

        $departements = Departement::all();

        return view('admin.stages.terminer', compact('stage', 'tuteurs', 'departements'));
    }

    /**
     * Mise à jour d’un stage.
     */
   public function update(Request $request, Stage $stage)
    {
        $request->validate([
            'rapport_stage_fichier' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'note_finale' => 'nullable|numeric|min:0|max:20',
        ]);

        $data = [];

        // Upload du rapport
        if ($request->hasFile('rapport_stage_fichier')) {
            $path = $request->file('rapport_stage_fichier')->store('rapports', 'public');
            $data['rapport_stage_fichier'] = $path;
        }

        // Note finale
        if ($request->filled('note_finale')) {
            $data['note_finale'] = $request->note_finale;
        }

        // Statut = terminé
        $data['statut'] = Stage::STATUTS['TERMINE'];

        $stage->update($data);

        return redirect()->route('rh.stages.en_cours', $stage->id)
            ->with('success', 'Le stage a été terminé avec succès.');
    }

    /**
     * Suppression d’un stage.
     */
    public function destroy($id)
    {
        $stage = Stage::findOrFail($id);
        $stage->delete();

        return redirect()->route('stages.index')->with('success', 'Stage supprimé.');
    }
    public function stagesAcademiques()
{
    $stages = Stage::with(['candidature.candidat', 'tuteur'])
        ->whereHas('candidature.candidat', function($q) {
            $q->where('type_depot', 'stage académique');
        })
        ->latest()
        ->get();

    return view('admin.stages.academique', ['stages' => $stages, 'typeDepot' => 'stage académique']);
}

public function stagesProfessionnels()
{
    $stages = Stage::with(['candidature.candidat', 'tuteur'])
        ->whereHas('candidature.candidat', function($q) {
            $q->where('type_depot', 'stage professionnel');
        })
        ->latest()
        ->get();

    return view('admin.stages.professionnel', ['stages' => $stages, 'typeDepot' => 'stage professionnel']);
}

public function stagesPreembauche()
{
    $stages = Stage::with(['candidature.candidat', 'tuteur'])
        ->whereHas('candidature.candidat', function($q) {
            $q->where('type_depot', 'stage de préembauche');
        })
        ->latest()
        ->get();

    return view('admin.stages.preambauche', ['stages' => $stages, 'typeDepot' => 'stage de préembauche']);
}
// Dans StageController.php
    public function stagesParDepartement()
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403);
        }

        $stages = Stage::whereNull('id_tuteur') // pas encore de tuteur
            ->where('id_departement', $directeur->id_departement)
            ->with(['candidature.candidat'])
            ->latest()
            ->get();

        // Récupérer les tuteurs du même département
        $tuteurs = User::whereHas('roles', function ($q) {
            $q->where('name', 'TUTEUR');
        })
        ->where('id_departement', $directeur->id_departement)
        ->get();

        return view('admin.stages.directeurs.stages_attente_tuteur', compact('stages', 'tuteurs'));
    }

    public function stagesAvecTuteur()
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403);
        }

        $stages = Stage::whereNotNull('id_tuteur') // tuteur affecté
            ->where('id_departement', $directeur->id_departement)
            ->with(['candidature.candidat', 'tuteur'])
            ->latest()
            ->get();

        return view('admin.stages.directeurs.stages_avec_tuteur', compact('stages'));
    }
    public function candidatsStagesEnCours()
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403, 'Accès non autorisé');
        }

        // Stages avec tuteur, du même département que le directeur
        $stages = Stage::whereNotNull('id_tuteur')
            ->where('id_departement', $directeur->id_departement)
            ->with(['candidature.candidat']) // on récupère le candidat lié à chaque stage
            ->latest()
            ->get();

        // Extraire uniquement les candidats à partir des stages
        $candidats = $stages->pluck('candidature.candidat')->filter();

        return view('admin.stages.directeurs.candidats_stages_en_cours', compact('candidats'));
    }

    public function listerTuteursDepartement()
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403, 'Accès refusé.');
        }

        $tuteurs = User::whereHas('roles', function ($q) {
                $q->where('name', 'TUTEUR');
            })
            ->where('id_departement', $directeur->id_departement)
            ->get();

        return view('admin.stages.directeurs.liste_tuteurs', compact('tuteurs'));
    }
   public function stagesEnAttentePourRH()
{
    $rh = Auth::user();

    if (!$rh->hasRole('RH')) {
        abort(403, 'Accès non autorisé');
    }

    $stages = Stage::with(['candidature.candidat', 'candidature.offre', 'departement'])
        ->where('statut', Stage::STATUTS['EN_ATTENTE']) // ou 'en_attente'
        ->whereNull('id_tuteur')
        ->latest()
        ->get();

    return view('admin.stages.rh.en_attente_tuteur', compact('stages'));
}
public function stagesEnCoursPourRH()
{
    $rh = Auth::user();

    if (!$rh->hasRole('RH')) {
        abort(403, 'Accès non autorisé');
    }

    // Récupérer les types de dépôt distincts parmi les candidats des stages en cours
    $typesDepot = Candidat::whereHas('candidatures.stage', function($q) {
        $q->where('statut', Stage::STATUTS['EN_COURS'])
          ->whereNotNull('id_tuteur');
    })->distinct()->pluck('type_depot')->filter();

    $stagesParType = [];

    foreach ($typesDepot as $typeDepot) {
        $stagesParType[$typeDepot] = Stage::with(['candidature.candidat', 'candidature.offre', 'tuteur', 'departement'])
            ->where('statut', Stage::STATUTS['EN_COURS'])
            ->whereNotNull('id_tuteur')
            ->whereHas('candidature.candidat', function($query) use ($typeDepot) {
                $query->where('type_depot', $typeDepot);
            })
            ->latest()
            ->get();
    }

    return view('admin.stages.rh.en_cours', compact('typesDepot', 'stagesParType'));
}
    public function stagesEnCoursPourtuteur()
    {
        $tuteur = Auth::user();

        if (!$tuteur->hasRole('TUTEUR')) {
            abort(403);
        }

        $stages = Stage::whereNotNull('id_tuteur') // tuteur affecté
            ->where('id_departement', $tuteur->id_departement)
            ->with(['candidature.candidat', 'tuteur', 'formulaire']) // on ajoute la relation 'formulaire'
            ->latest()
            ->get();

        return view('admin.stages.tuteur.en_cours', compact('stages'));
    }

    public function candidatsEnStage()
    {
        $user = Auth::user();
        if (!$user->hasRole('RH')) {
            abort(403, 'Accès non autorisé');
        }

        // On récupère les stages en cours avec leurs candidats
        $stages = Stage::with(['candidature.candidat'])
            ->where('statut', Stage::STATUTS['EN_COURS'])
            ->whereNotNull('id_tuteur')
            ->latest()
            ->get();

        // Option 1: Passer la liste des stages et récupérer candidats en Blade
        // Option 2: Extraire la liste unique des candidats ici
        // Exemple extraction candidats uniques :
        $candidats = $stages->pluck('candidature.candidat')->unique('id')->values();

        return view('admin.stages.rh.candidats_en_stage', compact('candidats'));
    }

    public function candidatsTuteur()
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('TUTEUR')) {
            abort(403, 'Accès non autorisé');
        }

        // Stages avec tuteur, du même département que le directeur
        $stages = Stage::whereNotNull('id_tuteur')
            ->where('id_departement', $directeur->id_departement)
            ->with(['candidature.candidat']) // on récupère le candidat lié à chaque stage
            ->latest()
            ->get();

        // Extraire uniquement les candidats à partir des stages
        $candidats = $stages->pluck('candidature.candidat')->filter();

        return view('admin.stages.tuteur.liste_candidats', compact('candidats'));
    }
    public function details_candidat_encours_tuteur($id)
    {

    $candidat = Candidat::findOrFail($id);

    // On récupère le stage actif lié à ce candidat via les candidatures
    $stage = Stage::whereHas('candidature', function ($query) use ($id) {
        $query->where('candidat_id', $id);
    })->where('statut', Stage::STATUTS['EN_COURS'])->latest('date_debut')->first();

    $progression = null;

    if ($stage && $stage->date_debut && $stage->date_fin) {
        $total = $stage->date_debut->diffInDays($stage->date_fin);
        $ecoules = $stage->date_debut->diffInDays(min(Carbon::today(), $stage->date_fin));
        $progression = $total > 0 ? round(($ecoules / $total) * 100) : 0;
    }

    return view('admin.stages.tuteur.details_candidat', compact('candidat', 'stage', 'progression'));
    }

    public function details_candidat_encours_directeur($id)
    {

    $candidat = Candidat::findOrFail($id);

    // On récupère le stage actif lié à ce candidat via les candidatures
    $stage = Stage::whereHas('candidature', function ($query) use ($id) {
        $query->where('candidat_id', $id);
    })->where('statut', Stage::STATUTS['EN_COURS'])->latest('date_debut')->first();

    $progression = null;

    if ($stage && $stage->date_debut && $stage->date_fin) {
        $total = $stage->date_debut->diffInDays($stage->date_fin);
        $ecoules = $stage->date_debut->diffInDays(min(Carbon::today(), $stage->date_fin));
        $progression = $total > 0 ? round(($ecoules / $total) * 100) : 0;
    }

    return view('admin.stages.directeurs.details_candidat_directeur', compact('candidat', 'stage', 'progression'));
    }
    public function validerParDirecteur($reponseId)
    {

        $reponse = ReponseFormulaire::findOrFail($reponseId);

        // Ici, récupérer le stage lié à la réponse
        $stage = $reponse->stage ?? null; // à adapter selon relation

        if (!$stage) {
            return response()->json(['success' => false, 'message' => 'Stage non trouvé'], 404);
        }

        $user = Auth::user();

        if (!$user->hasRole('DIRECTEUR')) {
            return response()->json(['success' => false, 'message' => 'Accès refusé'], 403);
        }

        $stage->update(['validation_directeur' => true]);

        return response()->json(['success' => true]);
    }
    public function stagesTerminesPourRH()
    {
        $stagesTermines = Stage::with(['candidature.candidat', 'candidature.offre', 'tuteur'])
            ->where('statut', 'termine')
            ->get()
            ->groupBy(function ($stage) {
                return $stage->type_depot ?? 'inconnu';
            });

        $typesDepot = $stagesTermines->keys();

        return view('admin.stages.rh.stagestermines', [
            'stagesParType' => $stagesTermines,
            'typesDepot' => $typesDepot,
        ]);
    }
}
