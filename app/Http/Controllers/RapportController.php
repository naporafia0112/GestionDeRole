<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidat;
use App\Models\Stage;
use App\Models\Candidature;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CandidatsExport;
use App\Exports\StagesExport;
use App\Exports\CandidaturesExport;

class RapportController extends Controller
{
    public function form()
    {
        return view('admin.rapports.form');
    }

    public function generer(Request $request)
    {
        $type = $request->get('rapport_type');

       if ($type === 'candidats') {
            $query = Candidat::query();

            if ($request->filled('type_depot')) {
                $query->where('type_depot', $request->type_depot);
            }

            if ($request->filled('date_debut')) {
                $query->whereDate('created_at', '>=', $request->date_debut);
            }

            if ($request->filled('date_fin')) {
                $query->whereDate('created_at', '<=', $request->date_fin);
            }

            if (!$query->exists()) {
                return back()->with('no_data', 'Aucune donnée trouvée pour ce filtre.');
            }

            // Passer la collection filtrée à l'export plutôt que la requête brute
            return Excel::download(new CandidatsExport($request), 'candidats.xlsx');
        }

        if ($type === 'stages') {
            $query = Stage::query();

            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            }

            if (!$query->exists()) {
                return back()->with('no_data', 'Aucun stage trouvé pour ce filtre.');
            }

            return Excel::download(new StagesExport($request), 'stages.xlsx');
        }

        if ($type === 'candidatures') {
            $query = Candidature::where('statut', 'valide');

            if ($request->filled('type_depot')) {
                $query->whereHas('candidat', function ($q) use ($request) {
                    $q->where('type_depot', $request->type_depot);
                });
            }

            if (!$query->exists()) {
                return back()->with('no_data', 'Aucune candidature trouvée pour ce filtre.');
            }

            return Excel::download(new CandidaturesExport($request), 'candidatures.xlsx');
        }

        return back()->with('error', 'Type de rapport invalide.');
    }
}
