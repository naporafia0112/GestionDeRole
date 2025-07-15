@extends('layouts.home')

@section('content')
<div class="container mt-4">
            <!-- Titre -->
            <div class="page-title-box mb-4">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.tuteur') }}">DIPRH</a></li>
                        <li class="breadcrumb-item active">Liste des formulaires d'évaluation</li>
                    </ol>
                </div>
                <h4 class="page-title"><strong>Liste des formulaires d’évaluation</strong></h4>
            </div>

            <!-- Liste ou vide -->
            @if($formulaires->isEmpty())
                <div class="alert alert-info">Aucun formulaire disponible.</div>
            @else
                <div class="timeline">
                    @foreach($formulaires as $formulaire)
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="mdi mdi-file-document-outline text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="mb-1">{{ $formulaire->titre }}</h5>
                                <p class="mb-1 text-muted">Créé par : {{ $formulaire->createur->name ?? 'Inconnu' }}</p>
                                <p class="mb-2 text-muted"><i class="mdi mdi-calendar-clock"></i> {{ $formulaire->created_at->format('d/m/Y à H\hi') }}</p>
                                <a href="{{ route('tuteur.formulaires.details', $formulaire->id) }}" class="btn btn-secondary btn-sm">
                                    Faire le rapport
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    margin-left: 30px;
    border-left: 3px solid #dee2e6;
    padding-left: 15px;
}
.timeline-item {
    position: relative;
    margin-bottom: 30px;
    padding-left: 25px;
}
.timeline-item::before {
    content: "";
    position: absolute;
    left: -11px;
    top: 5px;
    width: 20px;
    height: 20px;
    background-color: #353637;
    border: 3px solid rgb(177, 231, 238);
    border-radius: 50%;
    z-index: 1;
}
.timeline-icon {
    position: absolute;
    left: -21px;
    top: 0;
    width: 30px;
    height: 30px;
    background-color: #2c2d2f;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.timeline-content {
    background: white;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
</style>
@endpush
