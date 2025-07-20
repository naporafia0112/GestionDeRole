@extends('layouts.vitrine.vitrine')

@php
    use Illuminate\Support\Facades\Storage;
    $hasFile = $offre->fichier && Storage::disk('public')->exists($offre->fichier);
@endphp

@section('content')
<div class="container mt-5">
    <div class="row g-4">
        <!-- Partie principale -->
        <div class="{{ $hasFile ? 'col-lg-8' : 'col-12' }}">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="{{ route('vitrine.catalogue') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left me-2"></i> Retour aux offres
                        </a>
                        <a href="{{ route('candidature.create', $offre->id) }}" class="btn btn-primary rounded-pill">
                            <i class="bi bi-send-check me-2"></i> Postuler à cette offre
                        </a>
                    </div>

                    <h2 class="fw-bold text-primary">{{ $offre->titre }}</h2>
                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Localisation</p>
                            <p><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $offre->localisation->pays }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Date de publication</p>
                            <p><i class="bi bi-calendar-event me-1"></i>{{ $offre->date_publication->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Date limite</p>
                            <p><i class="bi bi-clock-history me-1"></i>{{ $offre->date_limite->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-secondary">Description du poste</h5>
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($offre->description)) !!}
                        </div>
                    </div>

                    <div>
                        <h5 class="text-secondary">Exigences du poste</h5>
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($offre->exigences)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($hasFile)
        <!-- Colonne PDF -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="fw-bold text-center mb-3">Fiche de poste (PDF)</h5>

                    <div class="mb-3 text-center">
                        <embed src="{{ asset('storage/'.$offre->fichier) }}" type="application/pdf" width="100%" height="320px" class="rounded shadow-sm">
                    </div>

                    <div class="d-grid">
                        <a href="{{ asset('storage/'.$offre->fichier) }}" class="btn btn-outline-primary rounded-pill" download>
                            <i class="bi bi-download me-2"></i>Télécharger le PDF
                            ({{ round(Storage::disk('public')->size($offre->fichier) / 1024) }} KB)
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
