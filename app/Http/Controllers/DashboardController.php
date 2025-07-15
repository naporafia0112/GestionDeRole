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



class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
    public function dashboardDirecteur(Request $request)
    {
        // Récupération de l'utilisateur connecté
        $directeur = Auth::user();

        // Sécurité : seuls les directeurs peuvent accéder
        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403, 'Accès non autorisé');
        }

        // Département du directeur connecté (filtrage)
        $departementId = $directeur->id_departement;

        // Récupération de la période de filtrage (ex: ?days=30)
        $days = $request->input('days');

        // --- 1. Compteurs globaux ---

        $countEnAttente = Stage::where('id_departement', $departementId)
            ->where('statut', Stage::STATUTS['EN_ATTENTE'])
            ->whereNull('id_tuteur')
            ->count();

        $countEnCours = Stage::where('id_departement', $departementId)
            ->where('statut', Stage::STATUTS['EN_COURS'])
            ->whereNotNull('id_tuteur')
            ->count();

        $countTermines = Stage::where('id_departement', $departementId)
            ->where('statut', Stage::STATUTS['TERMINE'])
            ->count();

        $countstagestotal = $countEnCours + $countEnAttente;

        // --- 2. Nombre de candidats en stage en cours dans ce département ---
        $countCandidats = Candidat::whereHas('candidatures.stage', function ($query) use ($departementId) {
            $query->where('id_departement', $departementId)
                ->where('statut', Stage::STATUTS['EN_COURS'])
                ->whereNotNull('id_tuteur');
        })->distinct('id')->count('id');

        // --- 3. Récupération des stages en attente récents ---
        $stagesEnAttenteQuery = Stage::with(['departement', 'candidature.candidat', 'candidatureSpontanee.candidat'])
            ->where('id_departement', $departementId)
            ->where('statut', Stage::STATUTS['EN_ATTENTE'])
            ->whereNull('id_tuteur');

        // Si un filtre temporel est appliqué (ex: 7 ou 30 jours)
        if ($days) {
            $stagesEnAttenteQuery->where('date_debut', '>=', Carbon::now()->subDays($days));
        }

        $stagesEnAttente = $stagesEnAttenteQuery->latest()->take(6)->get();

        return view('dashboard.dashboarddirecteur', compact(
            'countEnAttente',
            'countEnCours',
            'countTermines',
            'countCandidats',
            'countstagestotal',
            'stagesEnAttente'
        ));
}

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

        // Nombre de candidats avec au moins une candidature classique en stage en cours avec tuteur
        $countCandidats = $candidats->filter(function ($candidat) {
            return $candidat->candidatures->contains(function ($c) {
                return $c->stage
                    && $c->stage->statut === Stage::STATUTS['EN_COURS']
                    && $c->stage->id_tuteur !== null;
            });
        })->count();

        // Comptages globaux
        $countEnAttente = Stage::where('statut', Stage::STATUTS['EN_ATTENTE'])
            ->whereNull('id_tuteur')->count();

        $countEnCours = Stage::where('statut', Stage::STATUTS['EN_COURS'])->count();

        $countTermines = Stage::where('statut', Stage::STATUTS['TERMINE'])->count();

        $countstagestotal = $countEnCours + $countEnAttente;

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
            'countstagestotal',
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

public function dashboardTuteur()
{
    $tuteur = Auth::user();

    if (!$tuteur->hasRole('TUTEUR')) {
        abort(403, 'Accès non autorisé');
    }

    // Nombre de candidats actuellement en stage (classique + spontané) avec ce tuteur
    $stagesEnCours = Stage::where('id_tuteur', $tuteur->id)
        ->where('statut', Stage::STATUTS['EN_COURS'])
        ->with(['candidature.candidat', 'candidatureSpontanee.candidat'])
        ->get();

    $stagesTermines = Stage::where('id_tuteur', $tuteur->id)
        ->where('statut', Stage::STATUTS['TERMINE'])
        ->with(['candidature.candidat', 'candidatureSpontanee.candidat'])
        ->get();

    $countCandidatsEnCours = $stagesEnCours->map(function ($stage) {
        return $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat;
    })->filter()->unique('id')->count();

    $countCandidatsTermines = $stagesTermines->map(function ($stage) {
        return $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat;
    })->filter()->unique('id')->count();

    return view('dashboard.dashboardtuteur', compact(
        'countCandidatsEnCours',
        'countCandidatsTermines'
    ));
}


}
