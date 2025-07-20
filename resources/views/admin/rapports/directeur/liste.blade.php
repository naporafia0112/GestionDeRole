@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="mb-1 fw-bold">Formulaires d'évaluation</h2>
            <p class="text-muted mb-0">Gérez vos formulaires d'évaluation</p>
        </div>
        <a href="{{ route('formulairedynamique.creation') }}" class="btn btn-success btn-sm mt-2 mt-md-0">
            <i data-feather="plus" class="me-1"></i> Créer un formulaire
        </a>
    </div>

    @if($formulaires->isEmpty())
        <div class="alert alert-info text-center">
            Aucun formulaire créé pour le moment.
        </div>
    @else
        <div class="timeline" id="timeline">
            @foreach($formulaires as $index => $formulaire)
                <div class="timeline-item {{ $index % 2 === 0 ? 'left' : 'right' }}" data-index="{{ $index }}">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content card shadow-sm">
                        <div class="card-body">
                            <div class="timeline-date text-muted">
                                {{ $formulaire->created_at->format('d/m/Y à H\hi') }}
                            </div>
                            <h5 class="card-title mb-2">{{ $formulaire->titre }}</h5>
                             <a href="{{ route('directeur.formulaires.reponses', $formulaire) }}" class="btn btn-secondary btn-sm">
                                <i data-feather="file-text" class="me-1"></i> Voir réponses
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
/* Container */
.timeline {
    position: relative;
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 1rem;
}

/* Ligne centrale verticale */
.timeline::before {
    content: '';
    position: absolute;
    width: 4px;
    background: linear-gradient(180deg, #6c6e71 0%, #6c6e71 100%);
    top: 0;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 4px;
    box-shadow: 0 0 8px rgba(30, 144, 255, 0.3);
}

/* Timeline Item commun */
.timeline-item {
    position: relative;
    width: 35%; /* largeur réduite */
    margin-bottom: 2rem; /* marge verticale réduite */
    opacity: 0;
    transition: all 0.5s ease, opacity 0.5s ease;
    cursor: default;
}

/* Apparition avec translation latérale */
.timeline-item.show.left {
    opacity: 1;
    transform: translateX(0);
    box-shadow: 0 10px 25px rgba(104, 104, 105, 0.15);
}
.timeline-item.show.right {
    opacity: 1;
    transform: translateX(0);
    box-shadow: 0 10px 25px rgba(94, 94, 94, 0.15);
}

/* Position à gauche */
.timeline-item.left {
    left: 0;
    transform: translateX(-80px);
    text-align: right;
}

/* Position à droite */
.timeline-item.right {
    left: 55%;
    transform: translateX(80px);
    text-align: left;
}

/* Marker - Cercle avec ombre */
.timeline-marker {
    position: absolute;
    top: 1.5rem;
    width: 14px; /* plus petit */
    height: 14px;
    background: #fff;
    border: 3px solid #6c6e71; /* bord plus fin */
    border-radius: 50%;
    box-shadow: 0 0 8px rgba(91, 91, 92, 0.4);
    z-index: 10;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

/* Marker à droite pour items gauche, à gauche pour items droite */
.timeline-item.left .timeline-marker {
    right: -30px;
}
.timeline-item.right .timeline-marker {
    left: -30px;
}

/* Flèche "pointe" sur la carte */
.timeline-content {
    background: #fff;
    border-radius: 12px;
    padding: 0.75rem 1rem; /* padding réduit */
    position: relative;
    border-left: 6px solid #6c6e71; /* plus fin */
    transition: box-shadow 0.3s ease, border-left-color 0.3s ease;
}

/* Flèche CSS */
.timeline-item.left .timeline-content::after,
.timeline-item.right .timeline-content::after {
    content: "";
    position: absolute;
    top: 1.5rem;
    width: 0;
    height: 0;
    border-style: solid;
}

/* Flèche pointant vers droite */
.timeline-item.left .timeline-content::after {
    right: -24px;
    border-width: 12px 0 12px 16px;
    border-color: transparent transparent transparent #6c6e71;
}

/* Flèche pointant vers gauche */
.timeline-item.right .timeline-content::after {
    left: -24px;
    border-width: 12px 16px 12px 0;
    border-color: transparent #6c6e71 transparent transparent;
}

/* Hover & focus state */
.timeline-content:hover, .timeline-content:focus-within {
    box-shadow: 0 15px 30px rgba(95, 96, 96, 0.25);
    border-left-color: #6c6e71;
    outline: none;
}

/* Title */
.card-title {
    font-weight: 700;
    font-size: 1.1rem; /* plus petit */
    color: #212529;
}

/* Date */
.timeline-date {
    font-size: 0.75rem; /* plus petit */
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    user-select: none;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Bouton Toggle */
.btn-toggle {
    margin-top: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    background-color: #6c6e71;
    border: none;
    border-radius: 6px;
    padding: 0.3rem 0.8rem; /* padding réduit */
    color: white;
    box-shadow: 0 3px 10px rgba(63, 63, 63, 0.5);
    user-select: none;
}
.btn-toggle:hover, .btn-toggle:focus {
    background-color: #6c6e71;
    box-shadow: 0 6px 16px rgba(63, 63, 63, 0.5);
    outline: none;
}

/* Réponses */
.responses {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: opacity 0.35s ease, max-height 0.35s ease;
    color: #495057;
    font-size: 0.95rem;
    padding-left: 0.75rem;
}
.responses.show {
    opacity: 1;
    max-height: 600px;
}

/* Responsive */
@media (max-width: 992px) {
    .timeline-item {
        width: 90% !important;
        left: 5% !important;
        transform: translateX(0) !important;
        text-align: left !important;
        margin-left: auto;
        margin-right: auto;
    }
    .timeline-item.left .timeline-marker,
    .timeline-item.right .timeline-marker {
        left: -30px !important;
        right: auto !important;
    }
    .timeline-content::after {
        display: none;
    }
}

/* Header & Button */
.header-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #d3d5d5 100%);
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.btn-create {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(40,167,69,0.35);
    background: linear-gradient(45deg, #28a745, #218838);
    border: none;
    color: white;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.btn-create:hover, .btn-create:focus {
    background: linear-gradient(45deg, #218838, #1e7e34);
    box-shadow: 0 8px 20px rgba(33,136,56,0.5);
    transform: translateY(-2px);
    outline: none;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Feather icons
    if(typeof feather !== 'undefined'){
        feather.replace();
    }

    // Animation zigzag apparition progressive
    const items = document.querySelectorAll('.timeline-item');
    items.forEach((item, idx) => {
        setTimeout(() => {
            item.classList.add('show');
            if(item.classList.contains('left')){
                item.style.transform = 'translateX(0)';
            } else {
                item.style.transform = 'translateX(0)';
            }
        }, idx * 250);
    });

    // Toggle réponses
    const toggles = document.querySelectorAll('.btn-toggle');
    toggles.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('aria-controls');
            const content = document.getElementById(targetId);
            if(!content) return;

            const shown = content.classList.toggle('show');
            btn.setAttribute('aria-expanded', shown);
            btn.innerHTML = shown
                ? '<i data-feather="chevron-up" class="me-1"></i> Masquer réponses'
                : '<i data-feather="file-text" class="me-1"></i> Voir réponses';

            if(typeof feather !== 'undefined'){
                feather.replace();
            }
        });
    });
});
</script>
@endpush

