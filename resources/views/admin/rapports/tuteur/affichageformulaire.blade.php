@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="header-section mb-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-1 fw-bold">Formulaires d'évaluation</h2>
                <p class="text-muted mb-0">Gérez vos formulaires d'évaluation</p>
            </div>
            <a href="{{ route('formulairedynamique.creation') }}" class="btn btn-success btn-sm mt-2 mt-md-0 btn-create">
                <i data-feather="plus" class="me-1"></i> Créer un formulaire
            </a>
        </div>
    </div>

    @if($formulaires->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i data-feather="file-text" class="icon-lg"></i>
            </div>
            <h4 class="mb-2">Aucun formulaire disponible</h4>
            <p class="text-muted mb-4">Commencez par créer votre premier formulaire d'évaluation</p>
            <a href="{{ route('formulairedynamique.creation') }}" class="btn btn-success btn-create">
                <i data-feather="plus" class="me-2"></i>Créer un formulaire
            </a>
        </div>
    @else
        <div class="timeline" id="timeline">
            @foreach($formulaires as $index => $formulaire)
                <div class="timeline-item {{ $index % 2 === 0 ? 'left' : 'right' }}" data-index="{{ $index }}">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content card shadow-sm">
                        <div class="card-body p-3">
                            <div class="timeline-date text-muted mb-1">
                                <i data-feather="calendar" class="icon-sm me-1"></i>
                                {{ $formulaire->created_at->format('d/m/Y à H\hi') }}
                            </div>
                            <h5 class="card-title mb-2" style="font-size: 0.95rem;">{{ $formulaire->titre }}</h5>
                            <div class="creator-info mb-2" style="font-size: 0.75rem;">
                                <i data-feather="user" class="icon-sm me-1"></i>
                                <span>{{ $formulaire->createur->name ?? 'Inconnu' }}</span>
                            </div>
                            <a href="{{ route('tuteur.formulaires.details', $formulaire->id) }}" class="btn btn-secondary btn-sm btn-create" style="padding: 0.25rem 0.5rem; font-size: 0.7rem;">
                                <i data-feather="file-text" class="me-1"></i> Faire le rapport
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    max-width: 100%;
    margin: 1.5rem auto;
    padding: 0;
}

/* Ligne verticale centrale - plus visible */
.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    width: 4px;
    background: #3a86ff; /* Couleur bleue plus vive */
    transform: translateX(-50%);
    z-index: 0;
    box-shadow: 0 0 8px rgba(58, 134, 255, 0.4); /* Effet de lueur */
}

/* Timeline items - taille réduite */
.timeline-item {
    position: relative;
    width: 45%; /* Légèrement réduit pour laisser plus d'espace à la barre */
    margin-bottom: 1.5rem;
    z-index: 1;
    opacity: 0;
    transition: all 0.5s ease;
}

/* Cards - taille réduite */
.timeline-content {
    border-radius: 8px;
    padding: 0;
    border-left: 3px solid #6c6e71;
    background: white;
}

.timeline-item.left {
    float: left;
    clear: both;
    text-align: right;
    margin-right: 4%; /* Espace supplémentaire pour la barre */
}

.timeline-item.right {
    float: right;
    clear: both;
    text-align: left;
    margin-left: 4%; /* Espace supplémentaire pour la barre */
}

/* Marker - taille réduite mais plus visible */
.timeline-marker {
    position: absolute;
    top: 0.8rem;
    width: 14px;
    height: 14px;
    background: #3a86ff; /* Même couleur que la barre */
    border: 2px solid white;
    border-radius: 50%;
    z-index: 2;
    box-shadow: 0 0 0 2px #3a86ff; /* Contour bleu */
}

.timeline-item.left .timeline-marker {
    right: -9px; /* Position ajustée pour être centré sur la barre */
}
.timeline-item.right .timeline-marker {
    left: -9px; /* Position ajustée pour être centré sur la barre */
}

/* Animation */
.timeline-item.show {
    opacity: 1;
    transform: translateY(0);
}

/* Styles texte - taille réduite */
.card-title {
    font-size: 0.95rem;
    font-weight: bold;
    margin-bottom: 0.3rem;
}
.timeline-date {
    font-size: 0.75rem;
    margin-bottom: 0.3rem;
}
.creator-info {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}
.btn-create {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

/* Responsive */
@media (max-width: 992px) {
    .timeline::before {
        left: 8px;
        width: 3px;
    }

    .timeline-item,
    .timeline-item.left,
    .timeline-item.right {
        float: none;
        width: 90%;
        margin-left: 1.5rem;
        margin-right: 0;
        text-align: left;
    }

    .timeline-marker {
        left: -22px !important;
        right: auto !important;
        top: 0.7rem;
    }
}

/* Header et empty state - taille réduite */
.header-section {
    padding: 1rem;
}
.empty-state {
    padding: 1.5rem;
}
.empty-icon i {
    width: 36px;
    height: 36px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    if (typeof feather !== 'undefined') feather.replace();
    const items = document.querySelectorAll('.timeline-item');
    items.forEach((item, idx) => {
        setTimeout(() => {
            item.classList.add('show');
        }, idx * 200);
    });
});
</script>
@endpush
