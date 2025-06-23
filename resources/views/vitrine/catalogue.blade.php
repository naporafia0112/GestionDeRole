@extends('layouts.vitrine.vitrine')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Catalogue des Offres</h3>
<div class="col-auto">
                                <a href="{{ route('vitrine.index') }}" class="btn btn-sm btn-link">
                                    <i class="bi bi-arrow-left"></i> Retour
                                </a>
                            </div>
    @forelse($offres as $offre)
        <div class="card mb-3 shadow-sm">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">{{ $offre->titre }}</h5>
                    <small><strong>La description du stage:</strong>{{ Str::limit($offre->description, 120) }}</small><br>
                    <small><strong>Lieu:</strong> {{ $offre->localisation->pays ?? 'Non précisé' }}</small><br>
                    <small><strong>Date limite:</strong> {{ $offre->date_limite->format('d/m/Y') }}</small>
                    <a href="{{ route('vitrine.show', $offre->id) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Aucune offre trouvée.</div>
    @endforelse

    <div class="mt-4">
        {{ $offres->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
