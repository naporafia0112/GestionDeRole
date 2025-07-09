<?php

namespace App\Exports;

use App\Models\Candidat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CandidatsExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Candidat::query();

        #if ($this->request->filled('type_stage')) {
            #$query->where('type_stage', $this->request->type_stage);
        #}

        if ($this->request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $this->request->date_debut);
        }

        if ($this->request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $this->request->date_fin);
        }

        return view('admin.rapports.tables.candidats', [
            'candidats' => $query->get()
        ]);
    }
}

