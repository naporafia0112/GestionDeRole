@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Liste des formulaires de rapports</h2>
        <a href="{{ route('formulairedynamique.creation') }}" class="btn btn-success btn-sm">
            <i data-feather="plus" class="me-1"></i> Créer un formulaire
        </a>
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
                                {{ $formulaire->created_at->format('d/m/Y') }}
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
    .timeline {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px 0;
    }

    .timeline::after {
        content: '';
        position: absolute;
        width: 2px;
        background-color: #e9ecef;
        top: 0;
        bottom: 0;
        left: 50%;
        margin-left: -1px;
    }

    .timeline-item {
        padding: 10px 40px;
        position: relative;
        width: 50%;
    }

    .timeline-item::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        background-color: #fff;
        border: 3px solid rgb(177, 231, 238);
        border-radius: 50%;
        top: 15px;
        z-index: 1;
    }

    .timeline-item.left {
        left: 0;
    }

    .timeline-item.left::after {
        right: -10px;
    }

    .timeline-item.right {
        left: 50%;
    }

    .timeline-item.right::after {
        left: -10px;
    }

    .timeline-content {
        padding: 15px;
        border-radius: 6px;
    }

    .timeline-date {
        font-size: 0.8rem;
        margin-bottom: 5px;
    }

    @media screen and (max-width: 768px) {
        .timeline::after {
            left: 31px;
        }

        .timeline-item {
            width: 100%;
            padding-left: 70px;
            padding-right: 25px;
        }

        .timeline-item.left::after,
        .timeline-item.right::after {
            left: 21px;
        }

        .timeline-item.right {
            left: 0;
        }
    }
</style>
@endpush

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
