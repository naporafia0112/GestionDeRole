<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class GraphesExport implements FromArray
{
    protected $charts;

    public function __construct(array $charts)
    {
        $this->charts = $charts;
    }

    public function array(): array
    {
        return [
            ['Nom du graphique'],
            ...array_map(fn($c) => [$c], $this->charts)
        ];
    }
}
