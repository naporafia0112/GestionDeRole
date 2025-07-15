@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="header-section mb-5 w-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 fw-bold">Formulaires d'évaluation</h2>
                    <p class="text-muted mb-0">Gérez vos formulaires d'évaluation</p>
                </div>
                <a href="{{ route('formulairedynamique.creation') }}" class="btn btn-success btn-sm">
                    <i data-feather="plus" class="me-1"></i> Créer un formulaire
                </a>
            </div>
        </div>
    </div>
    
    @if($formulaires->isEmpty())
        <div class="alert alert-info text-center">
            Aucun formulaire créé pour le moment.
        </div>
    @else
        <div class="timeline">
            @foreach($formulaires as $index => $formulaire)
                <div class="timeline-item {{ $index % 2 === 0 ? 'left' : 'right' }}">
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

.timeline {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 1rem 0;
}

.timeline::before {
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
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.timeline-item:hover {
    transform: translateY(-6px);
    z-index: 2;
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

.timeline-content {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
}

.timeline-content:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 12px rgba(0,0,0,0.1);
}

.timeline-content .card-body {
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

/* Responsive */
@media screen and (max-width: 768px) {
    .header-section {
        padding: 1rem;
    }

    .header-section .d-flex {
        flex-direction: column;
        gap: 1rem;
    }

    .timeline::before {
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
}

@media screen and (max-width: 576px) {
    .btn-create span,
    .btn-action span {
        display: none;
    }

    .timeline-content .card-body {
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
    });
</script>
@endpush
