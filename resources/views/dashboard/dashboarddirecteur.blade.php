@extends('layouts.home')

@section('content')
<div class="container-fluid">

    <!-- Titre + filtre date -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Dashboard Directeur</h4>
                <form method="GET" class="d-flex align-items-center">
                    <div class="input-group input-group-sm">
                        <select class="form-select" name="days" onchange="this.form.submit()">
                            <option value="">Toutes les dates</option>
                            <option value="7" {{ request('days') == 7 ? 'selected' : '' }}>7 derniers jours</option>
                            <option value="30" {{ request('days') == 30 ? 'selected' : '' }}>30 derniers jours</option>
                            <option value="90" {{ request('days') == 90 ? 'selected' : '' }}>90 derniers jours</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row mt-2">
        <!-- Stages en attente -->
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card shadow">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                            <i class="fe-clock font-22 avatar-title text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3 text-end">
                        <h3 class="text-dark"><span data-plugin="counterup">{{ $countEnAttente }}</span></h3>
                        <p class="text-muted mb-0">Stages en attente</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stages en cours -->
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card shadow">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                            <i class="fe-check-circle font-22 avatar-title text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3 text-end">
                        <h3 class="text-dark"><span data-plugin="counterup">{{ $countEnCours }}</span></h3>
                        <p class="text-muted mb-0">Stages en cours</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Candidats -->
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card shadow">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                            <i class="fe-users font-22 avatar-title text-info"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3 text-end">
                        <h3 class="text-dark"><span data-plugin="counterup">{{ $countCandidats }}</span></h3>
                        <p class="text-muted mb-0">Candidats en stage</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total stages -->
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card shadow">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                            <i class="fe-briefcase font-22 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3 text-end">
                        <h3 class="text-dark"><span data-plugin="counterup">{{ $countstagestotal }}</span></h3>
                        <p class="text-muted mb-0">Stages actifs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique donut -->
    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="header-title mb-3">Répartition des stages</h4>
                    <div dir="ltr">
                        <div id="stages-status-chart" class="apex-charts" data-colors="#f7b84b,#0acf97,#727cf5"></div>
                    </div>
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

        <!-- Derniers stages en attente -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="header-title mb-3">Derniers stages en attente</h4>
                    @forelse ($stagesEnAttente as $stage)
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mt-0 mb-1">
                                    {{ $stage->sujet }} — 
                                    <span class="text-muted small">{{ optional($stage->candidat ?? $stage->candidatureSpontanees->candidat)->nom ?? 'Candidat inconnu' }}</span>
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
<!-- ApexCharts -->
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter
        $('[data-plugin="counterup"]').counterUp({
            delay: 10,
            time: 1000
        });

        // Donut chart
        var options = {
            series: [{{ $countEnAttente }}, {{ $countEnCours }}, {{ $countTermines }}],
            chart: {
                type: 'donut',
                height: 250
            },
            labels: ['En attente', 'En cours', 'Terminés'],
            colors: ['#f7b84b', '#0acf97', '#727cf5'],
            legend: {
                position: 'bottom'
            }
        };

        var chart = new ApexCharts(document.querySelector("#stages-status-chart"), options);
        chart.render();
    });
</script>
@endsection
