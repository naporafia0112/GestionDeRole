<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\Entretien;
use App\Models\Stage;
use Carbon\Carbon;
use App\Models\Candidat;
use App\Models\Entreprise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\candidatureSpontanee;
use Illuminate\Support\Collection;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        if (!$admin->hasRole('ADMIN')) {
            abort(403);
        }

        $months = collect(range(1, 12))->mapWithKeys(fn($m) => [$m => Carbon::create()->month($m)->format('F')]);

        // Statistiques de base
        $countCandidats = Candidat::count();
        $countCandidatures = Candidature::count() + CandidatureSpontanee::count();
        $countUtilisateurs = User::count();

        // Progression (validé / total)
        $valide = Candidature::where('statut', 'valide')->count()
                + CandidatureSpontanee::where('statut', 'valide')->count();
        $rejete = Candidature::where('statut', 'rejete')->count()
                + CandidatureSpontanee::where('statut', 'rejete')->count();
        $retenu= Candidature::where('statut', 'retenu')->count()
                + CandidatureSpontanee::where('statut', 'retenu')->count();
        $reçue =CandidatureSpontanee::where('statut', 'reçue')->count();
        $en_cours = Candidature::where('statut', 'en_cours')->count();

        $progressionPourcent = $countCandidatures > 0 ? round(($valide / $countCandidatures) * 100, 1) : 0;

        // Candidatures par mois
        $candidaturesParMois = Candidature::selectRaw('MONTH(date_soumission) as mois, COUNT(*) as total')
            ->groupBy('mois')->pluck('total', 'mois');

        $spontaneesParMois = CandidatureSpontanee::selectRaw('MONTH(date_soumission) as mois, COUNT(*) as total')
            ->groupBy('mois')->pluck('total', 'mois');

        $chartLabels = $months->values();
        $chartDataOffres = $months->keys()->map(fn($m) => $candidaturesParMois[$m] ?? 0);
        $chartDataSpontanees = $months->keys()->map(fn($m) => $spontaneesParMois[$m] ?? 0);

        // Stages en cours par mois
        $stagesParMois = Stage::selectRaw('MONTH(date_debut) as mois, COUNT(*) as total')
            ->where('statut', Stage::STATUTS['EN_COURS'])
            ->groupBy('mois')->pluck('total', 'mois');

        $chartStagesEnCours = $months->keys()->map(fn($m) => $stagesParMois[$m] ?? 0);

        // Type de dépôt
        $typesDepot = Candidat::select('type_depot', DB::raw('COUNT(*) as total'))
            ->groupBy('type_depot')->pluck('total', 'type_depot');

        // Utilisateurs par rôle
        $usersByRole = User::with('roles')
            ->get()
            ->flatMap(fn($user) => $user->roles->pluck('name'))
            ->countBy();

        // Offres publiées par mois
        $offresParMois = Offre::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->groupBy('mois')->pluck('total', 'mois');

        $chartOffresParMois = $months->keys()->map(fn($m) => $offresParMois[$m] ?? 0);

        // Entretiens par statut
        $entretiensByStatut = Entretien::select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')->pluck('total', 'statut');

        $offresParMois = Offre::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->groupBy('mois')->pluck('total', 'mois');
        $chartOffresPubliees = $months->keys()->map(fn($m) => $offresParMois[$m] ?? 0);

        // Utilisateurs par rôle
        $rolesUsers = Role::withCount('users')->get();
        $rolesLabels = $rolesUsers->pluck('name');
        $rolesCounts = $rolesUsers->pluck('users_count');

        // Candidatures validées par département
        $validesParDept = Candidature::where('statut', 'valide')
            ->select('offre_id', DB::raw('COUNT(*) as total'))
            ->groupBy('offre_id')
            ->get()
            ->groupBy(fn($item) => optional($item->offre)->departement->nom ?? 'Inconnu')
            ->map(fn($group) => $group->sum('total'));

        $usersParDepartement = User::with('departement')
            ->get()
            ->groupBy(fn($user) => optional($user->departement)->nom ?? 'Inconnu')
            ->map(fn($group) => $group->count());

        $departementLabels = $usersParDepartement->keys();
        $departementCounts = $usersParDepartement->values();
        $stagesParDept = Stage::with('departement')
        ->select('id_departement', DB::raw('COUNT(*) as total'))
        ->groupBy('id_departement')
        ->get()
        ->groupBy(fn($s) => optional($s->departement)->nom ?? 'Inconnu')
        ->map(fn($group) => $group->sum('total'));

        // a. Tuteurs par département
       $tuteursParDepartement = User::with('departement')
        ->whereHas('roles', fn($q) => $q->where('name', 'TUTEUR'))
        ->get()
        ->groupBy(fn($user) => optional($user->departement)->nom ?? 'Inconnu')
        ->map(fn($group) => $group->count());


        // b. Nombre total d’utilisateurs
        $totalUtilisateurs = User::count();

        // c. Utilisateurs créés par mois (ex : Janvier, Février…)
        $utilisateursParMois = User::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mois"),
                DB::raw('count(*) as total')
            )
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois');

        // d. Stages par département
        $stagesParDepartement = Stage::select('departement', DB::raw('count(*) as total'))
            ->groupBy('departement')
            ->pluck('total', 'departement');

        $candidaturesSemaine = Candidature::whereBetween('date_soumission', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->selectRaw('DAYNAME(date_soumission) as jour, COUNT(*) as total')
            ->groupBy('jour')
            ->pluck('total', 'jour');

        $offresMois = Candidature::selectRaw('MONTH(date_soumission) as mois, COUNT(*) as total')
            ->groupBy('mois')->pluck('total', 'mois');

        $spontaneesMois = CandidatureSpontanee::selectRaw('MONTH(date_soumission) as mois, COUNT(*) as total')
            ->groupBy('mois')->pluck('total', 'mois');

        $stagesEnCours = Stage::where('statut', 'EN_COURS')
            ->selectRaw('MONTH(date_debut) as mois, COUNT(*) as total')
            ->groupBy('mois')->pluck('total', 'mois');

        $stagesTermines = Stage::where('statut', 'TERMINE')
            ->selectRaw('MONTH(date_debut) as mois, COUNT(*) as total')
            ->groupBy('mois')->pluck('total', 'mois');

        $usersParMois = User::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->groupBy('mois')->pluck('total', 'mois');

        return view('dashboard.dashboard', compact(
        'countCandidats',
        'countCandidatures',
        'countUtilisateurs',
        'valide',
        'rejete',
        'retenu',
        'reçue',
        'en_cours',
        'chartLabels',
        'chartDataOffres',
        'chartDataSpontanees',
        'chartStagesEnCours',
        'typesDepot',
        'chartOffresPubliees',
        'rolesLabels',
        'rolesCounts',
        'validesParDept',
        'departementLabels',
        'departementCounts',
        'progressionPourcent',
        'candidaturesParMois',
        'spontaneesParMois',
        'offresParMois',
        'usersByRole',
        'entretiensByStatut',
        'stagesParDept',
        'stagesParMois',
        'tuteursParDepartement',
        'totalUtilisateurs',
        'utilisateursParMois',
        'stagesParDepartement',
        'candidaturesSemaine',
        'offresMois',
        'spontaneesMois',
        'stagesEnCours',
        'stagesTermines',
        'usersParMois'
    ));

    }

    public function dashboardDirecteur(Request $request)
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403);
        }

        $departementId = $directeur->id_departement;
        $days = $request->input('days');

        // --- 1. Comptages des stages par statut ---
        $queryBase = Stage::where('id_departement', $departementId);

        $countEnAttente = (clone $queryBase)
            ->where('statut', Stage::STATUTS['EN_ATTENTE'])
            ->whereNull('id_tuteur')
            ->count();

        $countEnCours = (clone $queryBase)
            ->where('statut', Stage::STATUTS['EN_COURS'])
            ->whereNotNull('id_tuteur')
            ->count();

        $countTermines = (clone $queryBase)
            ->where('statut', Stage::STATUTS['TERMINE'])
            ->count();

        // Total des stages actifs (En attente + En cours)
        $countstagestotal = $countEnAttente + $countEnCours;

        // --- 2. Candidats ayant un stage en cours dans ce département ---
        $countCandidats = Stage::where('statut', Stage::STATUTS['EN_COURS'])
            ->where('id_departement', $departementId)
            ->whereNotNull('id_tuteur')
            ->get()
            ->pluck('candidat.id') // récupère les ID via l'attribut "getCandidatAttribute"
            ->filter()
            ->count();

        // --- 3. Récupération des stages en attente récents ---
        $stagesEnAttenteQuery = Stage::with(['departement', 'candidature.candidat', 'candidatureSpontanee.candidat'])
            ->where('id_departement', $departementId)
            ->where('statut', Stage::STATUTS['EN_ATTENTE'])
            ->whereNull('id_tuteur');

        if ($days) {
            $stagesEnAttenteQuery->where('date_debut', '>=', Carbon::now()->subDays($days));
        }

        $stagesEnAttente = $stagesEnAttenteQuery->latest()->take(6)->get();

        return view('dashboard.dashboarddirecteur', compact(
            'countEnAttente',
            'countEnCours',
            'countTermines',
            'countstagestotal',
            'countCandidats',
            'stagesEnAttente'
        ));
    }



    public function dashboardTuteur()
    {
        $tuteur = Auth::user();
        if (!$tuteur->hasRole('TUTEUR')) {
            abort(403);
            }

        // Récupère tous les stages assignés à ce tuteur
        $stages = Stage::with(['candidature.candidat', 'candidatureSpontanee.candidat'])
            ->where('id_tuteur', $tuteur->id)
            ->get();

        // Filtrage par statut
        $stagesEnCours = $stages->where('statut', Stage::STATUTS['EN_COURS']);
        $stagesTermines = $stages->where('statut', Stage::STATUTS['TERMINE']);

        // Comptage des candidats uniques
        $countCandidatsEnCours = $stagesEnCours->map(function ($stage) {
            return $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat;
        })->filter()->unique('id')->count();

        $countCandidatsTermines = $stagesTermines->map(function ($stage) {
            return $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat;
        })->filter()->unique('id')->count();

        return view('dashboard.dashboardtuteur', compact('countCandidatsEnCours', 'countCandidatsTermines'));
    }

    /**
     * Affichage du dashboard RH
     */
    public function dashboardRH(Request $request)
    {
         $rh = Auth::user();

        if (!$rh->hasRole('RH')) {
            abort(403, 'Accès non autorisé');
        }

        $days = $request->input('days');

        // Récupérer les candidats avec leurs candidatures et candidatures spontanées (avec stages pour candidatures classiques)
        $candidats = Candidat::with([
            'candidatures' => function ($q) {
                $q->select('id', 'candidat_id', 'created_at', 'statut', 'offre_id');
                $q->with('stage'); // Charger le stage lié à chaque candidature classique
            },
            'candidatureSpontanees' => function ($q) {
                $q->select('id', 'candidat_id', 'created_at', 'statut');
                // Si tu veux charger aussi les stages liés aux candidatures spontanées,
                // ajoute un with('stage') ici si tu as cette relation côté CandidatureSpontanee
                // Par exemple :
                // $q->with('stage');
            }
        ])->get();

         // Comptages par statut
        $countEnAttente = Stage::where('statut', Stage::STATUTS['EN_ATTENTE'])->count();
        $countEnCours = Stage::where('statut', Stage::STATUTS['EN_COURS'])->count();
        $countTermines = Stage::where('statut', Stage::STATUTS['TERMINE'])->count();

        // Total des candidats en stage
        $countCandidats = Candidat::count();


        // Liste des stages en attente (filtrage jours si présent)
        $stagesEnAttenteQuery = Stage::with('departement')
            ->where('statut', Stage::STATUTS['EN_ATTENTE'])
            ->whereNull('id_tuteur');

        if ($days) {
            $stagesEnAttenteQuery->where('date_debut', '>=', Carbon::now()->subDays($days));
        }

        $stagesEnAttente = $stagesEnAttenteQuery->latest()->take(6)->get();

        // Groupement par mois sur created_at pour les candidatures classiques
        $candidaturesParMois = $candidats->flatMap->candidatures
            ->groupBy(fn($c) => Carbon::parse($c->created_at)->month)
            ->map->count();

        // Groupement par mois sur created_at pour les candidatures spontanées
        $spontaneesParMois = $candidats->flatMap->candidatureSpontanees
            ->groupBy(fn($c) => Carbon::parse($c->created_at)->month)
            ->map->count();

        // Préparation des labels et données pour le graphique
        $months = collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => Carbon::create()->month($m)->format('F')]);
        $chartLabels = $months->values();
        $chartDataOffres = $months->keys()->map(fn ($m) => $candidaturesParMois[$m] ?? 0);
        $chartDataSpontanees = $months->keys()->map(fn ($m) => $spontaneesParMois[$m] ?? 0);

        // Totaux
        $totalValideOffre = $candidats->flatMap->candidatures->count();
        $totalValideSpontanee = $candidats->flatMap->candidatureSpontanees->count();

        // Top départements (basé sur stages)
        $topDepartements = Stage::select('id_departement', DB::raw('COUNT(*) as total'))
            ->whereNotNull('id_departement')
            ->groupBy('id_departement')
            ->orderByDesc('total')
            ->with('departement')
            ->take(5)
            ->get();

        // 5 derniers stages en cours avec relations correctes (attention au nom des relations)
        $dernierStagesEnCours = Stage::with([
            'candidature.candidat',        // relation candidature (classique) singular
            'candidatureSpontanee.candidat', // relation candidatureSpontanee (singulier)
            'tuteur'
        ])->where('statut', Stage::STATUTS['EN_COURS'])
        ->latest('date_debut')
        ->take(5)
        ->get();

        // Stats de progression sur la dernière semaine et dernier mois
        $now = Carbon::now();
        $lastWeekTotal = $candidats->flatMap->candidatures
            ->where('created_at', '>=', $now->copy()->subWeek())->count()
            + $candidats->flatMap->candidatureSpontanees
            ->where('created_at', '>=', $now->copy()->subWeek())->count();

        $lastMonthTotal = $candidats->flatMap->candidatures
            ->where('created_at', '>=', $now->copy()->subMonth())->count()
            + $candidats->flatMap->candidatureSpontanees
            ->where('created_at', '>=', $now->copy()->subMonth())->count();

        $targetCandidatures = 500;
        $totalStages = Stage::where('statut', '!=', Stage::STATUTS['ANNULE'])->count();
        $totalTermines = Stage::where('statut', Stage::STATUTS['TERMINE'])->count();
        $progressionPourcent = $totalStages > 0 ? round(($totalTermines / $totalStages) * 100, 1) : 0;

        return view('dashboard.dashboardrh', compact(
            'countEnAttente',
            'countEnCours',
            'countTermines',
            'countCandidats',
            'stagesEnAttente',
            'chartLabels',
            'chartDataOffres',
            'chartDataSpontanees',
            'totalValideOffre',
            'totalValideSpontanee',
            'topDepartements',
            'progressionPourcent',
            'dernierStagesEnCours',
            'targetCandidatures',
            'lastWeekTotal',
            'lastMonthTotal'
        ));
    }
}
