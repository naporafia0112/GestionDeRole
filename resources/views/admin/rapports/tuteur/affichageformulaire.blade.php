@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <!-- Header (pris de votre brouillon) -->
    <div class="header-section mb-5" style="background: #f8f9fa; padding: 1.5rem; border-radius: 12px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-1 fw-bold">Formulaires d'évaluation</h2>
                <p class="text-muted mb-0">Gérez vos formulaires d'évaluation</p>
            </div>
        </div>
    </div>

    @if($formulaires->isEmpty())
        <!-- Empty state (pris de votre brouillon) -->
        <div class="text-center p-5">
            <div class="mb-3">
                <i data-feather="file-text" style="width: 48px; height: 48px; color: #6c757d;"></i>
            </div>
            <h4 class="mb-2">Aucun formulaire disponible</h4>
        </div>
    @else
        {{-- Début de la Timeline (Structure de la liste <ul>/<li>) --}}
        <ul class="timeline">
            @foreach($formulaires as $index => $formulaire)
                <li class="{{ $index % 2 !== 0 ? 'timeline-inverted' : '' }}">

                    {{-- Le cercle sur la ligne centrale --}}
                    <div class="timeline-badge success"><i data-feather="file-text"></i></div>

                    {{-- Le panneau de contenu avec les données de votre brouillon --}}
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title">{{ $formulaire->titre }}</h4>
                            <p>
                                <small class="text-muted d-flex align-items-center">
                                    <i data-feather="calendar" class="feather-sm me-2"></i>
                                    Créé le {{ $formulaire->created_at->format('d/m/Y à H\hi') }}
                                </small>
                            </p>
                        </div>
                        <div class="timeline-body">
                             {{-- Info du créateur ajoutée ici --}}
                            <p class="creator-info">
                                <i data-feather="user" class="feather-sm me-2"></i>
                                Créé par : <strong>{{ $formulaire->createur->name ?? 'Inconnu' }}</strong>
                            </p>
                            <hr class="my-3">
                            {{-- Bouton "Faire le rapport" --}}
                            <a href="{{ route('tuteur.formulaires.details', $formulaire->id) }}" class="btn btn-primary btn-sm">
                                <i data-feather="edit-3" class="feather-sm me-1"></i> Faire le rapport
                            </a>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- Fin de la Timeline --}}
    @endif
</div>
@endsection

@push('styles')
{{-- Style CSS de la timeline en liste, animée et compacte --}}
<style>
/* Ligne centrale */
.timeline {
    list-style: none;
    padding: 20px 0;
    position: relative;
}

.timeline:before {
    top: 0;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 3px;
    background-color: #e9ecef;
    left: 50%;
    margin-left: -1.5px;
    border-radius: 3px;
}

/* Items de la timeline (li) avec animation */
.timeline > li {
    margin-bottom: 20px;
    position: relative;
    opacity: 0;
    transform: translateX(-40px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}
.timeline > li.timeline-inverted {
    transform: translateX(40px);
}
.timeline > li.is-visible {
    opacity: 1;
    transform: translateX(0);
}

.timeline > li:before, .timeline > li:after { content: " "; display: table; }
.timeline > li:after { clear: both; }

/* Panneaux de contenu */
.timeline > li .timeline-panel {
    width: 46%;
    float: left;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 20px;
    position: relative;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    background-color: #fff;
    transition: all 0.3s ease;
}
.timeline > li .timeline-panel:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* Flèches pointant vers la ligne */
.timeline > li .timeline-panel:before {
    position: absolute;
    top: 26px;
    right: -15px;
    display: inline-block;
    border-top: 15px solid transparent;
    border-left: 15px solid #dee2e6;
    border-right: 0 solid #dee2e6;
    border-bottom: 15px solid transparent;
    content: " ";
}
.timeline > li .timeline-panel:after {
    position: absolute;
    top: 27px;
    right: -14px;
    display: inline-block;
    border-top: 14px solid transparent;
    border-left: 14px solid #fff;
    border-right: 0 solid #fff;
    border-bottom: 14px solid transparent;
    content: " ";
}

/* Cercles sur la ligne */
.timeline > li .timeline-badge {
    color: #198754; /* Couleur de l'icône */
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    position: absolute;
    top: 16px;
    left: 50%;
    margin-left: -25px;
    background-color: #e9f5ec; /* Fond vert pâle */
    z-index: 100;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #fff;
}
.timeline-badge i { width: 24px; height: 24px; }

/* Items inversés (à droite) */
.timeline > li.timeline-inverted > .timeline-panel { float: right; }
.timeline > li.timeline-inverted > .timeline-panel:before {
    border-left-width: 0; border-right-width: 15px; left: -15px; right: auto;
}
.timeline > li.timeline-inverted > .timeline-panel:after {
    border-left-width: 0; border-right-width: 14px; left: -14px; right: auto;
}

/* Contenu des panneaux */
.timeline-title { margin-top: 0; font-weight: 600; }
.timeline-body > p { margin-bottom: 0.5rem; font-size: 0.9rem; color: #495057; }
.creator-info { font-size: 0.85rem; color: #6c757d; }
.feather-sm { width: 14px; height: 14px; vertical-align: -2px; }

/* Responsive */
@media (max-width: 767px) {
    .timeline:before { left: 40px; }
    .timeline > li { transform: translateX(-20px); }
    .timeline > li.timeline-inverted { transform: translateX(-20px); }
    .timeline > li .timeline-panel,
    .timeline > li.timeline-inverted > .timeline-panel {
        width: calc(100% - 80px);
        float: right;
    }
    .timeline > li .timeline-panel:before,
    .timeline > li.timeline-inverted > .timeline-panel:before {
        border-left-width: 0; border-right-width: 15px; left: -15px; right: auto;
    }
    .timeline > li .timeline-panel:after,
    .timeline > li.timeline-inverted > .timeline-panel:after {
        border-left-width: 0; border-right-width: 14px; left: -14px; right: auto;
    }
    .timeline > li .timeline-badge { left: 15px; margin-left: 0; }
}

/* Style pour le bouton Créer */
.btn-create {
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
}
.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(40, 167, 69, 0.35);
}
</style>
@endpush

@push('scripts')
{{-- Script d'animation performant avec IntersectionObserver --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    const timelineItems = document.querySelectorAll(".timeline > li");

    if (timelineItems.length > 0) {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        timelineItems.forEach(item => {
            observer.observe(item);
        });
    }
});
</script>
@endpush
