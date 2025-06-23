@extends('layouts.vitrine.vitrine')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Suivi de la candidature : {{ $candidature->uuid }}</h4>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Nom</th>
                            <th>Prénoms</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Offre</th>
                            <th>Statut</th>
                            <th>Date de dépôt</th>
                            <th>Type de dépôt</th>
                            <th>Quartier</th>
                            <th>Ville</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $candidature->candidat->nom }}</td>
                            <td>{{ $candidature->candidat->prenoms }}</td>
                            <td>{{ $candidature->candidat->email }}</td>
                            <td>{{ $candidature->candidat->telephone ?? 'Non renseigné' }}</td>
                            <td>{{ $candidature->offre->titre }}</td>
                            <td>
                                @php
                                    $status = strtolower($candidature->statut);
                                    $colorClass = match ($status) {
                                        'en cours de traitement' => 'badge bg-success',
                                        'brouillon' => 'badge bg-warning text-dark',
                                        'archivé' => 'badge bg-danger',
                                        default => 'badge bg-secondary',
                                    };
                                @endphp
                                <span class="{{ $colorClass }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td>{{ $candidature->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $candidature->candidat->type_depot }}</td>
                            <td>{{ $candidature->candidat->quartier ?? 'Non renseigné' }}</td>
                            <td>{{ $candidature->candidat->ville ?? 'Non renseigné' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
