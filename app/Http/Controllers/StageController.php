<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\User;
use App\Models\ReponseFormulaire;
use App\Models\Candidature;
use App\Models\CandidatureSpontanee;
use App\Models\Departement;
use App\Models\Candidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\TuteurAffecteMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\StageCreeMail;
use App\Exports\TousCandidatsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use App\Notifications\NouveauStageNotification;
use PhpOffice\PhpWord\IOFactory;

class StageController extends Controller
{
    /**
     * Affiche la liste des stages.
     */
    public function index()
    {
        $stages = Stage::with([
            'candidature.candidat',
            'candidatureSpontanee.candidat',
            'tuteur',
            'departement'
        ])->latest()->get();

        return view('admin.stages.index', compact('stages'));
    }

    /**
     * Formulaire de création d’un stage (RH).
     */
    public function create(Request $request)
    {
        $departements = Departement::all();

        if ($request->filled('id_candidature')) {
            $candidature = Candidature::with('candidat', 'offre')->findOrFail($request->id_candidature);
            return view('admin.stages.create', [
                'candidature' => $candidature,
                'offre' => $candidature->offre,
                'type' => 'classique',
                'departements' => $departements,
            ]);
        }

        if ($request->filled('candidature_spontanee_id')) {
            $candidature = CandidatureSpontanee::with('candidat')->findOrFail($request->candidature_spontanee_id);
            return view('admin.stages.create', [
                'candidature' => $candidature,
                'offre' => null,
                'type' => 'spontanee',
                'departements' => $departements,
            ]);
        }

        return redirect()->route('dashboard.RH')->with('error', 'Paramètres invalides pour la création du stage.');
    }

    /**
     * Enregistrement d’un nouveau stage.
     */

    public function store(Request $request)
    {
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
                        $dateDebut = Carbon::parse($request->input('date_debut'));
                        $dateFin = Carbon::parse($value);
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

        $data = $request->only([
            'date_debut',
            'date_fin',
            'sujet',
            'lieu',
            'id_departement',
            'statut',
            'remuneration',
        ]);

        // Statut par défaut
        $data['statut'] = $data['statut'] ?? Stage::STATUTS['EN_ATTENTE'];

        if ($request->type === 'classique') {
            $candidature = Candidature::with(['candidat', 'offre'])->find($request->id_candidature);
            if (!$candidature) {
                return back()->with('error', 'Candidature classique introuvable.');
            }

            // Vérifier doublon
            $stageExistant = Stage::whereHas('candidature', function ($query) use ($candidature) {
                $query->where('candidat_id', $candidature->candidat_id)
                    ->where('offre_id', $candidature->offre_id);
            })->whereIn('statut', [
                Stage::STATUTS['EN_COURS'],
                Stage::STATUTS['EN_ATTENTE'],
            ])->exists();

            if ($stageExistant) {
                return back()->with('error', 'Ce candidat a déjà un stage actif ou en attente pour cette offre.');
            }

            $data['id_candidature'] = $candidature->id;
            $data['id_candidature_spontanee'] = null;

            $stage = Stage::create($data);

            // Récupérer le candidat lié
            $candidat = $candidature->candidat;

        } else { // spontanee
            $candidatureSpontanee = CandidatureSpontanee::with('candidat')->find($request->id_candidature_spontanee);
            if (!$candidatureSpontanee) {
                return back()->with('error', 'Candidature spontanée introuvable.');
            }

            // Vérifier doublon
            $stageExistant = Stage::where('id_candidature_spontanee', $candidatureSpontanee->id)
                ->whereIn('statut', [
                    Stage::STATUTS['EN_COURS'],
                    Stage::STATUTS['EN_ATTENTE'],
                ])
                ->exists();

            if ($stageExistant) {
                return back()->with('error', 'Ce candidat a déjà un stage actif ou en attente pour cette candidature spontanée.');
            }

            $data['id_candidature_spontanee'] = $candidatureSpontanee->id;
            $data['id_candidature'] = null;

            $stage = Stage::create($data);

            $directeur = User::whereHas('roles', function ($query) {
                $query->where('name', 'directeur');
            })->where('departement_id', $stage->id_departement)->first();

            if ($directeur) {
                $directeur->notify(new \App\Notifications\NouveauStageNotification($stage));
            }

            // Récupérer le candidat lié
            $candidat = $candidatureSpontanee->candidat;
        }

        // ENVOI DU MAIL AU CANDIDAT
        if ($candidat && !empty($candidat->email)) {
            Mail::to($candidat->email)->send(new StageCreeMail($candidat, $stage));
        }

        return redirect()->route('rh.stages.en_cours')->with('success', 'Stage créé avec succès et mail envoyé au candidat.');
    }

    /**
     * Détail d’un stage (pour RH).
     */
    public function show(Stage $stage)
    {
        $numero = Stage::where('id', '<=', $stage->id)->count();

        $stage->load([
            'candidature.candidat' => function ($query) {
                if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(Candidat::class))) {
                    $query->withTrashed();
                }
            },
            'candidatureSpontanee.candidat' => function ($query) {
                if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(Candidat::class))) {
                    $query->withTrashed();
                }
            },
            'tuteur',
            'departement',
        ]);

        // Récupération candidat avec fallback candidature classique ou spontanée
        $candidat = $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat ?? null;

        return view('admin.stages.rh.details', compact('stage', 'numero', 'candidat'));
    }

    /**
     * Détail d’un stage (directeur).
     */
    public function detailstagedirecteur(Stage $stage)
    {
        $numero = Stage::where('id', '<=', $stage->id)->count();

        $stage->load([
            'candidature.candidat' => function ($query) {
                if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(Candidat::class))) {
                    $query->withTrashed();
                }
            },
            'candidatureSpontanee.candidat' => function ($query) {
                if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(Candidat::class))) {
                    $query->withTrashed();
                }
            },
            'tuteur',
            'departement',
        ]);

        $candidat = $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat ?? null;

        $cvValide = null;
        if ($candidat) {
            $candidatureValidee = $candidat->candidatures()->where('statut', 'valide')->first();
            if ($candidatureValidee) {
                $cvValide = $candidatureValidee->cv_fichier;
            }
        }

        return view('admin.stages.directeurs.detail_stage', compact('stage', 'numero', 'candidat', 'cvValide'));
    }

    /**
     * Détail d’un stage (tuteur).
     */
    public function detailstagetuteur(Stage $stage)
    {
        $numero = Stage::where('id', '<=', $stage->id)->count();

        $stage->load([
            'candidature.candidat' => function ($query) {
                if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(Candidat::class))) {
                    $query->withTrashed();
                }
            },
            'candidatureSpontanee.candidat' => function ($query) {
                if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(Candidat::class))) {
                    $query->withTrashed();
                }
            },
            'tuteur',
            'departement',
        ]);

        $candidat = $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat ?? null;

        $cvValide = null;
        if ($candidat) {
            $candidatureValidee = $candidat->candidatures()->where('statut', 'valide')->first();
            if ($candidatureValidee) {
                $cvValide = $candidatureValidee->cv_fichier;
            }
        }

        return view('admin.stages.tuteur.detail_stage_tuteur', compact('stage', 'numero', 'candidat', 'cvValide'));
    }

    /**
     * Affecter un tuteur à un stage (directeur uniquement).
     */
  public function affecterTuteur(Request $request, $id)
    {
        $stage = Stage::findOrFail($id);

        $user = Auth::user();

        if (!$user->hasRole('DIRECTEUR')) {
            abort(403);
        }

        $request->validate([
            'id_tuteur' => 'required|exists:users,id',
        ]);

        $tuteur = User::findOrFail($request->id_tuteur);

        $stage->id_tuteur = $tuteur->id;
        $stage->statut = Stage::STATUTS['EN_COURS'];
        $stage->save();

        // On suppose que la relation "candidature" existe dans le modèle Stage
        $candidature = $stage->candidature;

        Mail::to($tuteur->email)->send(new TuteurAffecteMail($tuteur, $candidature));

        return redirect()->route('stages.en_cours')->with('success', 'Tuteur affecté avec succès.');
    }

    /**
     * Formulaire d’édition d’un stage.
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
     * Mise à jour d’un stage (upload rapport, note finale, statut terminé).
     */
    public function update(Request $request, Stage $stage)
    {
        $request->validate([
            'rapport_stage_fichier' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'note_finale' => 'required|numeric|min:0|max:20',
        ]);

        $data = [];

        if ($request->hasFile('rapport_stage_fichier')) {
            $path = $request->file('rapport_stage_fichier')->store('rapports', 'public');
            $data['rapport_stage_fichier'] = $path;
        }

        if ($request->filled('note_finale')) {
            $data['note_finale'] = $request->note_finale;
        }

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

    /**
     * Liste des stages académiques.
     */
    public function stagesAcademiques()
    {
        $stages = Stage::with(['candidature.candidat', 'candidatureSpontanee.candidat', 'tuteur', 'departement'])
            ->whereHas('candidature.candidat', function ($q) {
                $q->where('type_depot', 'stage académique');
            })
            ->latest()
            ->get();

        return view('admin.stages.academique', ['stages' => $stages, 'typeDepot' => 'stage académique']);
    }

    /**
     * Liste des stages professionnels.
     */
    public function stagesProfessionnels()
    {
        $stages = Stage::with(['candidature.candidat', 'candidatureSpontanee.candidat', 'tuteur', 'departement'])
            ->whereHas('candidature.candidat', function ($q) {
                $q->where('type_depot', 'stage professionnel');
            })
            ->latest()
            ->get();

        return view('admin.stages.professionnel', ['stages' => $stages, 'typeDepot' => 'stage professionnel']);
    }

    /**
     * Liste des stages de préembauche.
     */
    public function stagesPreembauche()
    {
        $stages = Stage::with(['candidature.candidat', 'candidatureSpontanee.candidat', 'tuteur', 'departement'])
            ->whereHas('candidature.candidat', function ($q) {
                $q->where('type_depot', 'stage de préembauche');
            })
            ->latest()
            ->get();

        return view('admin.stages.preambauche', ['stages' => $stages, 'typeDepot' => 'stage de préembauche']);
    }

    /**
     * Liste des stages en attente de tuteur pour le directeur du département.
     */
    public function stagesParDepartement()
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403);
        }

        $stages = Stage::whereNull('id_tuteur')
            ->where('id_departement', $directeur->id_departement)
            ->with(['candidature.candidat', 'candidatureSpontanee.candidat'])
            ->latest()
            ->get();

        // Tuteurs même département
        $tuteurs = User::whereHas('roles', function ($q) {
            $q->where('name', 'TUTEUR');
        })
        ->where('id_departement', $directeur->id_departement)
        ->get();

        return view('admin.stages.directeurs.stages_attente_tuteur', compact('stages', 'tuteurs'));
    }

    /**
     * Liste des stages avec tuteur pour directeur.
     */
    public function stagesAvecTuteur()
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403, 'Accès non autorisé');
        }

        $stages = Stage::whereNotNull('id_tuteur')
            ->where('id_departement', $directeur->id_departement)
            ->where('statut', 'en_cours') // <- Statut "en cours"
            ->with(['candidature.candidat', 'candidatureSpontanee.candidat', 'tuteur'])
            ->latest()
            ->get();

        return view('admin.stages.directeurs.stages_avec_tuteur', compact('stages'));
    }

    public function stagesTermines()
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403, 'Accès non autorisé');
        }

        $stages = Stage::whereNotNull('id_tuteur')
            ->where('id_departement', $directeur->id_departement)
            ->where('statut', 'termine') // <- Statut "terminé"
            ->with(['candidature.candidat', 'candidatureSpontanee.candidat', 'tuteur'])
            ->latest()
            ->get();

        return view('admin.stages.directeurs.stages_termines', compact('stages'));
    }

    /**
     * Liste candidats des stages en cours pour directeur.
     */
    public function candidatsStagesEnCours()
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403, 'Accès non autorisé');
        }

        // On récupère les stages validés dans le département du directeur
        $stages = Stage::with(['candidature.candidat', 'candidatureSpontanee.candidat'])
            ->whereNotNull('id_tuteur')
            ->where('id_departement', $directeur->id_departement)
            ->get();

        // On extrait les candidats uniques à partir des stages
        $candidats = $stages->map(function ($stage) {
            $candidat = $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat;
            if ($candidat) {
                // Injecter le statut de stage dans le candidat (utile pour l'affichage)
                $candidat->statut_stage = $stage->statut ?? 'En cours';
            }
            return $candidat;
        })->filter()->unique('id')->values();

        return view('admin.stages.directeurs.candidats_stages_en_cours', compact('candidats'));
    }


    /**
     * Liste tuteurs du département du directeur.
     */
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

    /**
     * Liste des stages en attente (RH).
     */
    public function stagesEnAttentePourRH()
    {
        $rh = Auth::user();

        if (!$rh->hasRole('RH')) {
            abort(403, 'Accès non autorisé');
        }

        $stages = Stage::with(['candidature.candidat', 'candidature.offre', 'departement'])
            ->where('statut', Stage::STATUTS['EN_ATTENTE'])
            ->whereNull('id_tuteur')
            ->latest()
            ->get();

        return view('admin.stages.rh.en_attente_tuteur', compact('stages'));
    }

    /**
     * Liste des stages en cours (RH) par type dépôt.
     */
    public function stagesEnCoursPourRH()
    {
        $rh = Auth::user();

        if (!$rh->hasRole('RH')) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer les types_depot ayant au moins un stage en cours
        $typesDepot = Candidat::where(function ($query) {
            $query->whereHas('candidatures.stage', function ($q) {
                $q->where('statut', Stage::STATUTS['EN_COURS'])
                ->whereNotNull('id_tuteur');
            })
            ->orWhereHas('candidatureSpontanees.stage', function ($q) {
                $q->where('statut', Stage::STATUTS['EN_COURS'])
                ->whereNotNull('id_tuteur');
            });
        })->distinct()->pluck('type_depot')->filter();

        // Groupement des stages par type_depot
        $stagesParType = [];

        foreach ($typesDepot as $typeDepot) {
            $stagesParType[$typeDepot] = Stage::with([
                    'tuteur',
                    'departement',
                    'candidature.candidat',
                    'candidature.offre',
                    'candidatureSpontanee.candidat'
                ])
                ->where('statut', Stage::STATUTS['EN_COURS'])
                ->whereNotNull('id_tuteur')
                ->where(function ($query) use ($typeDepot) {
                    $query->whereHas('candidature.candidat', function ($q) use ($typeDepot) {
                        $q->where('type_depot', $typeDepot);
                    })->orWhereHas('candidatureSpontanee.candidat', function ($q) use ($typeDepot) {
                        $q->where('type_depot', $typeDepot);
                    });
                })
                ->latest()
                ->get();
        }

        return view('admin.stages.rh.en_cours', compact('typesDepot', 'stagesParType'));
    }


    /**
     * Liste des stages en cours (Tuteur).
     */
    public function stagesEnCoursPourTuteur()
    {
        $tuteur = Auth::user();

        if (!$tuteur->hasRole('TUTEUR')) {
            abort(403, 'Accès refusé');
        }

        $stages = Stage::where('id_tuteur', $tuteur->id)
            ->where('statut', 'en_cours')
            ->with(['candidature.candidat', 'candidatureSpontanee.candidat', 'formulaire'])
            ->latest()
            ->get();

        return view('admin.stages.tuteur.en_cours', compact('stages'));
    }

    public function stagesTerminesPourTuteur()
    {
        $tuteur = Auth::user();

        if (!$tuteur->hasRole('TUTEUR')) {
            abort(403, 'Accès refusé');
        }

        $stages = Stage::where('id_tuteur', $tuteur->id)
            ->where('statut', 'termine')
            ->with(['candidature.candidat', 'candidatureSpontanee.candidat', 'formulaire'])
            ->latest()
            ->get();

        return view('admin.stages.tuteur.termines', compact('stages'));
    }


    /**
     * Liste candidats en stage (RH).
     */
    public function candidatsEnStage()
    {
        $user = Auth::user();
        if (!$user->hasRole('RH')) {
            abort(403, 'Accès non autorisé');
        }

        $stages = Stage::with(['candidature.candidat', 'candidatureSpontanee.candidat'])
            ->where('statut', Stage::STATUTS['EN_COURS'])
            ->whereNotNull('id_tuteur')
            ->latest()
            ->get();

        $candidats = $stages->map(function ($stage) {
            return $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat;
        })->filter()->unique('id')->values();

        return view('admin.stages.rh.candidats_en_stage', compact('candidats'));
    }

    /**
     * Liste candidats (Tuteur).
     */
    public function candidatsTuteur()
    {
        $tuteur = Auth::user();

        if (!$tuteur->hasRole('TUTEUR')) {
            abort(403, 'Accès non autorisé');
        }

        // Récupère tous les stages encadrés par ce tuteur
        $stages = Stage::where('id_tuteur', $tuteur->id)
            ->with(['candidature.candidat', 'candidatureSpontanee.candidat'])
            ->latest()
            ->get();

        // Récupère les candidats associés à ces stages
        $candidats = $stages->map(function ($stage) {
            return $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat;
        })->filter()->unique('id')->values();

        return view('admin.stages.tuteur.liste_candidats', compact('candidats'));
    }

    /**
     * Détails candidat en stage pour tuteur.
     */

    public function details_candidat_encours_tuteur($id)
    {
        $candidat = Candidat::findOrFail($id);

        $stage = Stage::where(function($query) use ($id) {
            $query->whereHas('candidature', function ($q) use ($id) {
                $q->where('candidat_id', $id);
            })
            ->orWhereHas('candidatureSpontanee', function ($q) use ($id) {
                $q->where('candidat_id', $id);
            });
        })
        ->where('statut', Stage::STATUTS['EN_COURS'])
        ->latest('date_debut')
        ->first();

        $progression = null;

        if ($stage && $stage->date_debut && $stage->date_fin) {
            $dateDebut = Carbon::parse($stage->date_debut);
            $dateFin = Carbon::parse($stage->date_fin);
            $aujourdHui = Carbon::today();

            $total = $dateDebut->diffInDays($dateFin);
            $ecoules = $dateDebut->diffInDays(min($aujourdHui, $dateFin));

            $progression = $total > 0 ? round(($ecoules / $total) * 100) : 0;
        }

        return view('admin.stages.tuteur.details_candidat', compact('candidat', 'stage', 'progression'));
    }


    /**
     * Détails candidat en stage pour directeur.
     */
    public function details_candidat_encours_directeur($id)
    {
        $candidat = Candidat::findOrFail($id);

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

    /**
     * Validation par directeur.
     */
    public function validerParDirecteur($reponseId)
    {
        $reponse = ReponseFormulaire::findOrFail($reponseId);

        $stage = $reponse->stage ?? null;

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

    /**
     * Liste stages terminés (RH).
     */
    public function stagesTerminesPourRH()
    {
        $stagesTermines = Stage::with(['candidature.candidat', 'candidature.offre', 'tuteur'])
            ->where('statut', Stage::STATUTS['TERMINE'])
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
    public function exportTous(Request $request)
    {
        $query = Candidat::whereHas('candidatures.stage', function ($q) use ($request) {
        if ($request->filled('date_debut')) {
            $q->whereDate('date_debut', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $q->whereDate('date_debut', '<=', $request->date_fin);
        }
        });

        $candidats = $query->get();

        if ($candidats->isEmpty()) {
            return back()->with('no_data', 'Aucune donnée trouvée pour cette période.');
        }

        return Excel::download(new TousCandidatsExport($candidats), 'tous_les_candidats.xlsx');
    }


   public function exportPDF(Request $request)
    {
       $query = Candidat::whereHas('candidatures.stage', function ($q) use ($request) {
        if ($request->filled('date_debut')) {
            $q->whereDate('date_debut', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $q->whereDate('date_debut', '<=', $request->date_fin);
        }
        });

        $candidats = $query->get();

        if ($candidats->isEmpty()) {
            return redirect()->back()->with('no_data', 'Aucune donnée trouvée pour cette période.');
        }

        $pdf = Pdf::loadView('admin.rapports.candidats-pdf', compact('candidats'));
        return $pdf->download('candidats_complets.pdf');
    }


   public function exportWord(Request $request)
    {
        $query = Candidat::query();

        // Appliquer les filtres sur les dates
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $candidats = $query->get();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText("Liste des candidats", ['bold' => true, 'size' => 16]);

        $table = $section->addTable();

        // En-têtes
        $table->addRow();
        $table->addCell()->addText('ID');
        $table->addCell()->addText('Nom');
        $table->addCell()->addText('Prénom');
        $table->addCell()->addText('Email');
        $table->addCell()->addText('Téléphone');
        $table->addCell()->addText('Type dépôt');
        $table->addCell()->addText('Date création');

        foreach ($candidats as $candidat) {
            $table->addRow();
            $table->addCell()->addText($candidat->id);
            $table->addCell()->addText($candidat->nom);
            $table->addCell()->addText($candidat->prenoms);
            $table->addCell()->addText($candidat->email);
            $table->addCell()->addText($candidat->telephone);
            $table->addCell()->addText($candidat->type_depot);
            $table->addCell()->addText($candidat->created_at->format('d/m/Y'));
        }

        $fileName = 'candidats.docx';
        $tempFile = storage_path($fileName);
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
  public function imprimer(Request $request)
    {
        $query = Candidat::query();

        // Appliquer les filtres sur les dates
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $candidats = $query->get();

       return view('admin.rapports.candidats-print', compact('candidats'));
    }

}
