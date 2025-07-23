<?php

namespace App\Exports;

use App\Models\Stage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StagesExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Stage::query();

        if ($this->request->filled('statut')) {
            $query->where('statut', $this->request->statut);
        }

        return $query->get([
            'id', 'sujet', 'remuneration', 'date_debut', 'date_fin', 'statut', 'created_at'
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Titre', 'Rémuneration', 'Date début', 'Date fin', 'Statut', 'Date de création'];
    }
}
