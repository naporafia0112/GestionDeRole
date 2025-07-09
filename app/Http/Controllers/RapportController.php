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
            return Excel::download(new CandidatsExport($request), 'candidats.xlsx');
        }

        if ($type === 'stages') {
            return Excel::download(new StagesExport($request), 'stages.xlsx');
        }

        if ($type === 'candidatures') {
            return Excel::download(new CandidaturesExport($request), 'candidatures.xlsx');
        }

        return back()->with('error', 'Type de rapport invalide.');
    }
}
