@extends('layouts.home')

@section('content')
<div class="container-fluid">

    <!-- Titre + filtre date -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Dashboard Directeur</h4>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques principales -->
    <div class="row">
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-warning border-warning border me-3">
                        <i class="fe-clock font-22 avatar-title text-warning"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="text-dark mb-1">{{ $countEnAttente }}</h3>
                        <p class="text-muted mb-0">Stages en attente</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-success border-success border me-3">
                        <i class="fe-check-circle font-22 avatar-title text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="text-dark mb-1">{{ $countEnCours }}</h3>
                        <p class="text-muted mb-0">Stages en cours</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-info border-info border me-3">
                        <i class="fe-users font-22 avatar-title text-info"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="text-dark mb-1">{{ $countCandidats }}</h3>
                        <p class="text-muted mb-0">Candidats en stage</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Graphique donut + derniers stages en attente -->
    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="header-title mb-3">Répartition des stages</h4>
                    <div id="stages-status-chart" class="apex-charts" data-colors="#f7b84b,#0acf97,#727cf5"></div>
                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <p class="text-muted mb-1">En attente</p>
                            <h5 class="mb-0">{{ $countEnAttente }}</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted mb-1">En cours</p>
                            <h5 class="mb-0">{{ $countEnCours }}</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted mb-1">Terminés</p>
                            <h5 class="mb-0">{{ $countTermines }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="header-title mb-3">Derniers stages en attente</h4>
                    @forelse ($stagesEnAttente as $stage)
                        @php
                            $candidat = $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat ?? null;
                        @endphp
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mt-0 mb-1">
                                    {{ $stage->sujet }} —
                                    <span class="text-muted small">{{ $candidat ? $candidat->nom . ' ' . $candidat->prenoms : 'Candidat inconnu' }}</span>
                                </h5>
                                <p class="text-muted mb-0"><i class="fe-calendar me-1"></i> Début prévu : {{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}</p>
                            </div>
                            <div class="ms-3">
                                <span class="badge bg-warning text-dark">En attente</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Aucun stage en attente récemment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('[data-plugin="counterup"]').counterUp({ delay: 10, time: 1000 });

        new ApexCharts(document.querySelector("#stages-status-chart"), {
            series: [{{ $countEnAttente }}, {{ $countEnCours }}, {{ $countTermines }}],
            chart: { type: 'donut', height: 250 },
            labels: ['En attente', 'En cours', 'Terminés'],
            colors: ['#f7b84b', '#0acf97', '#727cf5'],
            legend: { position: 'bottom' }
        }).render();
    });
</script>
@endsection
