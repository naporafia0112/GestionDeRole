<?php

namespace App\Exports;

use App\Models\Candidat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TousCandidatsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Candidat::all(); // Prend tout le contenu
    }

    public function headings(): array
    {
        return [
            'ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Adresse',
            'Date naissance', 'Sexe', 'Type dépôt', 'Statut', 'Date création'
        ];
    }
}
