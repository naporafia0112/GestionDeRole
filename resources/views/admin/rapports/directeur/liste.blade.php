@extends('layouts.home')

@section('content')
<div class="container mt-4">
    {{-- En-tête de la page --}}
    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap">
        <div>
            <h2 class="mb-1 fw-bold">Formulaires d'évaluation</h2>
            <p class="text-muted mb-0">Historique de la création des formulaires</p>
        </div>
        <a href="{{ route('formulairedynamique.creation') }}" class="btn btn-success">
            <i data-feather="plus" class="me-1"></i> Créer un formulaire
        </a>
    </div>

    @if($formulaires->isEmpty())
        <div class="alert alert-info text-center">
            Aucun formulaire créé pour le moment.
        </div>
    @else
        {{-- Début de la Timeline --}}
        <ul class="timeline">
            @foreach($formulaires as $index => $formulaire)
                <li class="{{ $index % 2 !== 0 ? 'timeline-inverted' : '' }}">
                    <div class="timeline-badge success"><i data-feather="file-plus"></i></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title">{{ $formulaire->titre }}</h4>
                            <p>
                                <small class="text-muted">
                                    <i data-feather="clock" class="feather-sm"></i>
                                    Créé le {{ $formulaire->created_at->format('d/m/Y à H\hi') }}
                                </small>
                            </p>
                        </div>
                        <div class="timeline-body">
                            <p>Cliquez sur le bouton ci-dessous pour consulter les réponses pour ce formulaire.</p>
                            <a href="{{ route('directeur.formulaires.reponses', $formulaire) }}" class="btn btn-primary btn-sm mt-3">
                                <i data-feather="file-text" class="feather-sm me-1"></i> Voir rapports
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
<style>
/* ---------------------------------------------------
    TIMELINE STYLING (Animé et Compact)
-----------------------------------------------------*/

/* Ligne centrale */
.timeline {
    list-style: none;
    padding: 20px 0 20px;
    position: relative;
}

.timeline:before {
    top: 0;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 3px;
    background-color: #f1f1f1;
    left: 50%;
    margin-left: -1.5px;
    border-radius: 3px;
}

/* Items de la timeline (li) */
.timeline > li {
    margin-bottom: 20px;
    position: relative;

    /* STYLES POUR L'ANIMATION - ÉTAT INITIAL */
    opacity: 0;
    transform: translateX(-100px); /* Par défaut pour les éléments à gauche */
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}
.timeline > li.timeline-inverted {
    /* ÉTAT INITIAL pour les éléments à droite */
    transform: translateX(100px);
}
.timeline > li.is-visible {
    /* ÉTAT FINAL (quand l'élément est visible) */
    opacity: 1;
    transform: translateX(0);
}


.timeline > li:before,
.timeline > li:after {
    content: " ";
    display: table;
}
.timeline > li:after {
    clear: both;
}

/* Panneaux de contenu (CARTE RÉDUITE) */
.timeline > li .timeline-panel {
    width: 45%; /* Légèrement plus fin */
    float: left;
    border: 1px solid #e9ecef;
    border-radius: 10px; /* Bords plus arrondis */
    padding: 15px; /* Padding réduit */
    position: relative;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.07);
    background-color: #fff;
    transition: box-shadow 0.3s ease;
}
.timeline > li .timeline-panel:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* Flèches pointant vers la ligne centrale */
.timeline > li .timeline-panel:before {
    position: absolute;
    top: 21px; /* Ajusté pour le badge plus petit */
    right: -15px;
    display: inline-block;
    border-top: 15px solid transparent;
    border-left: 15px solid #e9ecef;
    border-right: 0 solid #e9ecef;
    border-bottom: 15px solid transparent;
    content: " ";
}
.timeline > li .timeline-panel:after {
    position: absolute;
    top: 22px; /* Ajusté */
    right: -14px;
    display: inline-block;
    border-top: 14px solid transparent;
    border-left: 14px solid #fff;
    border-right: 0 solid #fff;
    border-bottom: 14px solid transparent;
    content: " ";
}

/* Cercles sur la ligne centrale (BADGE RÉDUIT) */
.timeline > li .timeline-badge {
    color: #fff;
    width: 40px; /* Réduit */
    height: 40px; /* Réduit */
    line-height: 40px;
    font-size: 1.2em;
    text-align: center;
    position: absolute;
    top: 16px;
    left: 50%;
    margin-left: -20px; /* Ajusté (moitié de la nouvelle largeur) */
    background-color: #adb5bd;
    z-index: 100;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #fff;
}
.timeline-badge i {
    width: 20px; /* Icône plus petite */
    height: 20px;
}

/* Items inversés (à droite) */
.timeline > li.timeline-inverted > .timeline-panel {
    float: right;
}
.timeline > li.timeline-inverted > .timeline-panel:before {
    border-left-width: 0;
    border-right-width: 15px;
    left: -15px;
    right: auto;
}
.timeline > li.timeline-inverted > .timeline-panel:after {
    border-left-width: 0;
    border-right-width: 14px;
    left: -14px;
    right: auto;
}

/* Couleurs des badges */
.timeline-badge.success { background-color: #c9ebdb !important; }
/* ... autres couleurs ... */

/* Contenu du panneau (STYLES RÉDUITS) */
.timeline-title {
    margin-top: 0;
    color: #343a40;
    font-weight: 600;
    font-size: 1.1rem; /* Police du titre réduite */
}
.timeline-body > p {
    margin-bottom: 0;
    font-size: 0.9rem; /* Police du texte réduite */
    color: #6c757d;
}
.feather-sm { /* Classe utilitaire pour petites icônes */
    width: 14px; height: 14px; vertical-align: -2px;
}

/* Responsive (pas de changement ici, c'est déjà bon) */
@media (max-width: 767px) {
    .timeline:before { left: 35px; }
    .timeline > li {
        /* Animation pour mobile, toujours depuis la gauche */
        transform: translateX(-30px);
    }
    .timeline > li.timeline-inverted {
        transform: translateX(-30px);
    }
    .timeline > li .timeline-panel,
    .timeline > li.timeline-inverted > .timeline-panel {
        width: calc(100% - 75px);
        float: right;
    }
    .timeline > li .timeline-panel:before,
    .timeline > li.timeline-inverted > .timeline-panel:before {
        border-left-width: 0; border-right-width: 15px;
        left: -15px; right: auto;
    }
    .timeline > li .timeline-panel:after,
    .timeline > li.timeline-inverted > .timeline-panel:after {
        border-left-width: 0; border-right-width: 14px;
        left: -14px; right: auto;
    }
    .timeline > li .timeline-badge {
        left: 15px; margin-left: 0; top: 16px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    // 1. Rendre les icônes Feather
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // 2. Logique d'animation à l'apparition
    const timelineItems = document.querySelectorAll(".timeline > li");

    if (timelineItems.length > 0) {
        // Créer un "observateur" qui surveillera les éléments de la timeline
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                // Si l'élément est maintenant visible dans le viewport...
                if (entry.isIntersecting) {
                    // ...on lui ajoute la classe qui déclenche l'animation CSS
                    entry.target.classList.add("is-visible");
                    // On arrête de l'observer pour ne pas répéter l'animation
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1 // L'animation se déclenche quand 10% de l'élément est visible
        });

        // Demander à l'observateur de surveiller chaque item de la timeline
        timelineItems.forEach(item => {
            observer.observe(item);
        });
    }
});
</script>
@endpush
