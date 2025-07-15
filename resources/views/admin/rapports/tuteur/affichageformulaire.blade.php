@extends('layouts.home')
@section('content')
<div class="container mt-4">
    <!-- Header avec titre et bouton -->
    <div class="header-section mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold">Formulaires d'évaluation</h2>
                <p class="text-muted mb-0">Gérez vos formulaires d'évaluation</p>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    @if($formulaires->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i data-feather="file-text" class="icon-lg"></i>
            </div>
            <h4 class="mb-2">Aucun formulaire disponible</h4>
            <p class="text-muted mb-4">Commencez par créer votre premier formulaire d'évaluation</p>
            <a href="{{ route('formulairedynamique.creation') }}" class="btn btn-success">
                <i data-feather="plus" class="me-2"></i>Créer un formulaire
            </a>
        </div>
    @else
        <div class="timeline-modern">
            @foreach($formulaires as $index => $formulaire)
                <div class="timeline-item {{ $index % 2 === 0 ? 'left' : 'right' }}">
                    <div class="timeline-content">
                        <div class="timeline-marker"></div>
                        <div class="card timeline-card">
                            <div class="card-body">
                                <div class="timeline-date">
                                    <i data-feather="calendar" class="icon-sm me-1"></i>
                                    {{ $formulaire->created_at->format('d/m/Y à H\hi') }}
                                </div>
                                <h5 class="card-title">{{ $formulaire->titre }}</h5>
                                <div class="creator-info">
                                    <i data-feather="user" class="icon-sm me-1"></i>
                                    <span>{{ $formulaire->createur->name ?? 'Inconnu' }}</span>
                                </div>
                                <div class="action-section">
                                    <a href="{{ route('tuteur.formulaires.details', $formulaire->id) }}" class="btn btn-secondary btn-action">
                                        <i data-feather="file-text" class="me-2"></i>
                                        <span>Faire le rapport</span>
                                    </a>
                                </div>
                            </div>
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
  /* Header Section */
.header-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #d3d5d5 100%);
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.btn-create {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.12);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 12px;
    border: 2px dashed #e9ecef;
}

.empty-icon {
    margin-bottom: 1rem;
}

.icon-lg {
    width: 48px;
    height: 48px;
    color: #6c757d;
}

.icon-sm {
    width: 14px;
    height: 14px;
}

/* Timeline Modern */
.timeline-modern {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 1rem 0;
}

.timeline-modern::before {
    content: '';
    position: absolute;
    width: 3px;
    background: linear-gradient(to bottom, #e9ecef, #dee2e6);
    top: 0;
    bottom: 0;
    left: 50%;
    margin-left: -1.5px;
    border-radius: 2px;
}

.timeline-item {
    padding: 0.5rem 1rem;
    position: relative;
    width: 50%;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-item.left {
    left: 0;
    text-align: right;
}

.timeline-item.right {
    left: 50%;
    text-align: left;
}

.timeline-marker {
    position: absolute;
    width: 14px;
    height: 14px;
    background: #fff;
    border: 2px solid rgb(177, 231, 238);
    border-radius: 50%;
    top: 1.5rem;
    z-index: 2;
    box-shadow: 0 0 0 3px rgba(177, 231, 238, 0.2);
}

.timeline-item.left .timeline-marker {
    right: -6px;
}

.timeline-item.right .timeline-marker {
    left: -6px;
}

.timeline-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
}

.timeline-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 12px rgba(0,0,0,0.1);
}

.timeline-card .card-body {
    padding: 1rem;
}

.timeline-date {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #343a40;
}

.creator-info {
    display: flex;
    align-items: center;
    color: #6c757d;
    font-size: 0.8rem;
    margin-bottom: 0.75rem;
}

.action-section {
    display: flex;
}

.btn-action {
    padding: 0.4rem 0.9rem;
    font-size: 0.85rem;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.12);
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media screen and (max-width: 768px) {
    .header-section {
        padding: 1rem;
    }

    .header-section .d-flex {
        flex-direction: column;
        gap: 1rem;
    }

    .timeline-modern::before {
        left: 31px;
    }

    .timeline-item {
        width: 100%;
        padding-left: 60px;
        padding-right: 20px;
        text-align: left !important;
    }

    .timeline-item.left .timeline-marker,
    .timeline-item.right .timeline-marker {
        left: 20px;
    }

    .timeline-item.right {
        left: 0;
    }

    .timeline-date,
    .creator-info,
    .action-section {
        justify-content: flex-start !important;
    }

    .empty-state {
        padding: 1.5rem 1rem;
    }

    .icon-lg {
        width: 40px;
        height: 40px;
    }
}

@media screen and (max-width: 576px) {
    .btn-create span,
    .btn-action span {
        display: none;
    }

    .timeline-card .card-body {
        padding: 0.75rem;
    }
}

</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Animation d'apparition progressive des éléments
        const timelineItems = document.querySelectorAll('.timeline-item');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        timelineItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(30px)';
            item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(item);
        });
    });
</script>
@endpush
