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


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
   public function dashboardDirecteur(Request $request)
    {
        $directeur = Auth::user();

        if (!$directeur->hasRole('DIRECTEUR')) {
            abort(403, 'Accès non autorisé');
        }

        $departementId = $directeur->id_departement;

        // --- Récupère la valeur du filtre (7, 30 ou 90 jours) ---
        $days = $request->input('days'); // ex: ?days=30

        // --- Comptages principaux ---
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

        $countCandidats = Candidat::whereHas('candidatures.stage', function ($query) use ($departementId) {
            $query->whereNotNull('id_tuteur')
                ->where('statut', Stage::STATUTS['EN_COURS'])
                ->where('id_departement', $departementId);
        })->distinct('id')->count('id');

        $countstagestotal = $countEnCours + $countEnAttente;

        // --- Liste des stages en attente (avec filtre jours) ---
        $stagesEnAttenteQuery = Stage::with('departement')
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

        $departementId = $rh->id_departement;

        // --- Récupère la valeur du filtre (7, 30 ou 90 jours) ---
        $days = $request->input('days'); // ex: ?days=30

        // --- Comptages principaux ---
        $countEnAttente = Stage::
            where('statut', Stage::STATUTS['EN_ATTENTE'])
            ->whereNull('id_tuteur')
            ->count();

        $countEnCours = Stage::where('statut', Stage::STATUTS['EN_COURS'])
            ->whereNotNull('id_tuteur')
            ->count();

        $countTermines = Stage::where('id_departement', $departementId)
            ->where('statut', Stage::STATUTS['TERMINE'])
            ->count();

        $countCandidats = Candidat::whereHas('candidatures.stage', function ($query) {
            $query->whereNotNull('id_tuteur')
                ->where('statut', Stage::STATUTS['EN_COURS']);
        })->distinct('id')->count('id');

        $countstagestotal = $countEnCours + $countEnAttente;

        // --- Liste des stages en attente (avec filtre jours) ---
        $stagesEnAttenteQuery = Stage::with('departement')
            ->where('id_departement', $departementId)
            ->where('statut', Stage::STATUTS['EN_ATTENTE'])
            ->whereNull('id_tuteur');

        if ($days) {
            $stagesEnAttenteQuery->where('date_debut', '>=', Carbon::now()->subDays($days));
        }

        $stagesEnAttente = $stagesEnAttenteQuery->latest()->take(6)->get();

        $candidaturesChart = Candidature::select(
        DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mois"),
            DB::raw("COUNT(*) as total")
        )
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();

        $chartLabels = $candidaturesChart->pluck('mois');
        $chartData = $candidaturesChart->pluck('total');

        return view('dashboard.dashboardrh', compact(
            'countEnAttente',
            'countEnCours',
            'countTermines',
            'countCandidats',
            'countstagestotal',
            'stagesEnAttente',
            'chartLabels',
            'chartData'
        ));
    }

   public function dashboardTuteur()
{
    $tuteur = Auth::user();

    if (!$tuteur->hasRole('TUTEUR')) {
        abort(403, 'Accès non autorisé');
    }

    // Nombre de candidats actuellement en stage avec ce tuteur
    $countCandidatsEnCours = Candidat::whereHas('candidatures.stage', function ($query) use ($tuteur) {
        $query->where('id_tuteur', $tuteur->id)
              ->where('statut', Stage::STATUTS['EN_COURS']);
    })->distinct('id')->count('id');

    // Nombre de candidats ayant terminé leur stage avec ce tuteur
    $countCandidatsTermines = Candidat::whereHas('candidatures.stage', function ($query) use ($tuteur) {
        $query->where('id_tuteur', $tuteur->id)
              ->where('statut', Stage::STATUTS['TERMINE']);
    })->distinct('id')->count('id');

    return view('dashboard.dashboardtuteur', compact('countCandidatsEnCours', 'countCandidatsTermines'));
}

}
