<?php

namespace App\Exports;

use App\Models\Candidature;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CandidaturesExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Candidature::with('candidat')->where('statut', 'valide');

        if ($this->request->filled('type_depot')) {
            $query->whereHas('candidat', function ($q) {
                $q->where('type_depot', $this->request->type_depot);
            });
        }

        return $query->get()->map(function ($candidature) {
            return [
                'ID' => $candidature->id,
                'Nom Candidat' => $candidature->candidat?->nom ?? 'N/A',
                'Prénom Candidat' => $candidature->candidat?->prenom ?? 'N/A',
                'Statut' => $candidature->statut,
                'Date de création' => $candidature->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nom Candidat', 'Prénom Candidat', 'Statut', 'Date de création'];
    }
}
