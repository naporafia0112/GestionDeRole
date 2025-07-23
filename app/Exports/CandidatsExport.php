<?php

namespace App\Exports;

use App\Models\Candidat;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CandidatsExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Candidat::query();

        if ($this->request->filled('type_depot')) {
            $query->where('type_depot', $this->request->type_depot);
        }

        if ($this->request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $this->request->date_debut);
        }

        if ($this->request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $this->request->date_fin);
        }

        return $query->get([
            'id', 'nom', 'prenoms', 'email', 'telephone', 'type_depot', 'created_at'
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Type de dépôt', 'Date de création'];
    }
}
