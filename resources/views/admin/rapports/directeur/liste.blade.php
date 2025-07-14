@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Mes formulaires créés</h2>
        <a href="{{ route('formulairedynamique.creation') }}" class="btn btn-success btn-sm">
            <i data-feather="plus" class="me-1"></i> Créer un formulaire
        </a>
    </div>

    @if($formulaires->isEmpty())
        <div class="alert alert-info text-center">
            Aucun formulaire créé pour le moment.
        </div>
    @else
        <div class="row">
            @foreach($formulaires as $formulaire)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="mb-3">
                                <h5 class="card-title mb-1">{{ $formulaire->titre }}</h5>
                                <small class="text-muted">Créé le {{ $formulaire->created_at->format('d/m/Y') }}</small>
                            </div>
                            <a href="{{ route('directeur.formulaires.reponses', $formulaire) }}" class="btn btn-secondary btn-sm align-self-start">
                                <i data-feather="file-text" class="me-1"></i>Voir réponse
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Init Feather icons
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
