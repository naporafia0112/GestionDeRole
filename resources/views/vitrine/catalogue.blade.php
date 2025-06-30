@extends('layouts.vitrine.vitrine')

@section('content')
<div class="container my-5">
    <!-- Titre amélioré -->
    <div class="text-center mb-5">
        <h2 class="fw-bold display-5 mb-3" style="color: #2c3e50;">Catalogue des Offres</h2>
        <p class="lead text-muted">Découvrez nos opportunités professionnelles exclusives</p>
        <div class="divider mx-auto" style="width: 80px; height: 3px; background: linear-gradient(to right, #3498db, #2c3e50);"></div>
    </div>

    <!-- Conteneur principal modernisé -->
    <div class="p-4 rounded-4 shadow-sm" style="background-color: #f8fafc; border: 1px solid rgba(0,0,0,0.05);">
        <!-- Bouton retour stylisé -->
        <div class="mb-4">
            <a href="{{ route('vitrine.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Retour à l'accueil
            </a>
        </div>

        <!-- Grille améliorée -->
        <div class="row g-4">
            @forelse($offres as $offre)
                <div class="col-md-12">
                    <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100 hover-effect">
                        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-start" style="background-color: #ffffff;">
                            <div class="me-3 flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <h5 class="card-title mb-0 fw-bold" style="color: #2c3e50;">
                                        <i class="bi bi-briefcase-fill me-2" style="color: #3498db;"></i>
                                        {{ $offre->titre }}
                                    </h5>
                                </div>

                                <p class="card-text text-muted mb-3">{{ Str::limit($offre->description, 150) }}</p>

                                <div class="d-flex flex-wrap gap-3">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-geo-alt-fill me-2" style="color: #e74c3c;"></i>
                                        <span>{{ $offre->localisation->pays ?? 'Non précisé' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-clock-fill me-2" style="color: #f39c12;"></i>
                                        <span>Expire le {{ $offre->date_limite->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <a href="{{ route('vitrine.show', $offre->id) }}" class="btn btn-primary rounded-pill px-4">
                                    Voir détail <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="alert alert-light border rounded-4" style="background-color: rgba(255,255,255,0.7);">
                        <i class="bi bi-info-circle-fill me-2" style="color: #3498db;"></i>
                        Aucune offre disponible actuellement
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination améliorée -->
        <div class="d-flex justify-content-center mt-5">
            {{ $offres->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
