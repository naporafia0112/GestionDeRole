@extends('layouts.home')

@section('content')
<div class="container-fluid">

    <!-- Titre et filtre date -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="page-title">Dashboard RH</h4>
            <form class="d-flex align-items-center">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control border" id="dash-daterange" placeholder="Sélectionnez une période">
                    <span class="input-group-text bg-primary border-primary text-white">
                        <i class="mdi mdi-calendar-range"></i>
                    </span>
                </div>
                <button type="submit" class="btn btn-primary btn-sm ms-2" title="Filtrer">
                    <i class="mdi mdi-filter"></i> Appliquer
                </button>
            </form>
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
                        <small class="text-muted">(Stages actuellement en attente d'un tuteur)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-success border-success border me-3">
                        <i class="fe-more-horizontal font-22 avatar-title text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="text-dark mb-1">{{ $countEnCours }}</h3>
                        <p class="text-muted mb-0">Stages en cours</p>
                         <small class="text-muted">(Stages en cours d'éxécution )</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-success border-success border me-3">
                        <i class="fe-check-circle font-22 avatar-title text-danger"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="text-dark mb-1">{{ $countTermines }}</h3>
                        <p class="text-muted mb-0">Stages Terminés</p>
                         <small class="text-muted">(Stages terminés par le Responsable RH)</small>
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
                        <p class="text-muted mb-0">Candidats Actuel</p>
                        <small class="text-muted">(Distincts candidats ayant fait un depôt de candidatures)</small>
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
                        <h3 class="text-dark mb-1">{{ $totalValideOffre }}</h3>
                        <p class="text-muted mb-0">Candidatures á offres</p>
                         <small class="text-muted">(Candidures liés á des offres)</small>
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
                        <h3 class="text-dark mb-1">{{ $totalValideSpontanee }}</h3>
                        <p class="text-muted mb-0">Candidatures spontanées</p>
                         <small class="text-muted">(Candidatures libres, qui ne sont pas associées a une offre)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Graphiques résumé -->
    <div class="row">
        <!-- Donut: Statut des stages -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Répartition des stages par statut</h5>
                    <div id="stages-status-chart" class="apex-charts mb-3" data-colors="#f7b84b,#0acf97,#727cf5"></div>
                    <div class="row text-center">
                        <div class="col-4">
                            <p class="text-muted mb-1">En attente</p>
                            <h5>{{ $countEnAttente }}</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted mb-1">En cours</p>
                            <h5>{{ $countEnCours }}</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted mb-1">Terminés</p>
                            <h5>{{ $countTermines }}</h5>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">Visualisation des stages selon leur statut actuel</small>
                </div>
            </div>
        </div>

        <!-- Top 5 départements -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Top 5 départements par nombre de stagiaires</h5>
                    <canvas id="departementsChart" height="230"></canvas>
                    <small class="text-muted d-block mt-2">Les départements les plus actifs en termes de stages</small>
                </div>
            </div>
        </div>

        <!-- Progression globale -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title mb-4">Progression des stages terminés</h5>
                    <div class="progress w-100" style="height: 30px;">
                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                            style="width: {{ $progressionPourcent }}%;" aria-valuenow="{{ $progressionPourcent }}"
                            aria-valuemin="0" aria-valuemax="100">
                            {{ $progressionPourcent }}%
                        </div>
                    </div>
                    <small class="text-muted mt-3 text-center">
                        Pourcentage des stages terminés par rapport aux stages totaux (hors annulés).
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques des candidatures -->
    <div class="row">
        <!-- Barres par mois -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Candidatures valides reçues par mois</h5>
                    <canvas id="barChartCandidatures" height="120"></canvas>
                    <small class="text-muted d-block mt-2">
                        Répartition mensuelle des candidatures valides pour les offres classiques (en bleu) et les candidatures spontanées (en vert).
                    </small>
                </div>
            </div>
        </div>

        <!-- Camembert répartition types -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Répartition globale des candidatures valides</h5>
                    <canvas id="pieChartTypes" height="120"></canvas>
                    <small class="text-muted d-block mt-2">
                        Part relative entre candidatures sur offres et candidatures spontanées.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des derniers stages en cours -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">5 derniers stages en cours dans votre département</h5>
            @if($dernierStagesEnCours->isEmpty())
                <p class="text-danger">Aucun stage en cours pour le moment.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Candidat</th>
                                <th>Tuteur</th>
                                <th>Date de début</th>
                                <th>Sujet du stage</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($dernierStagesEnCours as $stage)
                            <tr>
                                <td>{{ $stage->candidat?->nom  ?? 'N/A' }} {{ $stage->candidat?->prenoms  ?? 'N/A' }}</td>
                                <td>{{ $stage->tuteur?->name ?? 'Non attribué' }}</td>
                                <td>{{ $stage->date_debut?->format('d/m/Y') ?? 'N/A' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($stage->sujet, 50) }}</td>
                                <td>
                                <span class="badge bg-success text-white">{{ ucfirst(str_replace('_', ' ', $stage->statut)) }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <small class="text-muted d-block mt-3">Suivi des stages actifs avec leur tuteur assigné.</small>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.counterup/2.1.0/jquery.counterup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Animation compteur
    $('[data-plugin="counterup"]').counterUp({ delay: 100, time: 1200 });

    // Donut : Statut stages
    new ApexCharts(document.querySelector("#stages-status-chart"), {
        series: [{{ $countEnAttente }}, {{ $countEnCours }}, {{ $countTermines }}],
        chart: { type: 'donut', height: 250 },
        labels: ["En attente", "En cours", "Terminés"],
        colors: ["#f7b84b", "#0acf97", "#727cf5"],
        legend: { show: false },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: { width: 200 },
                legend: { position: 'bottom' }
            }
        }]
    }).render();

    // Barres mensuelles candidatures valides
    new Chart(document.getElementById('barChartCandidatures'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Candidatures Offres',
                    data: {!! json_encode($chartDataOffres) !!},
                    backgroundColor: '#4e73df'
                },
                {
                    label: 'Candidatures Spontanées',
                    data: {!! json_encode($chartDataSpontanees) !!},
                    backgroundColor: '#1cc88a'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Nombre' } },
                x: { title: { display: true, text: 'Mois' } }
            }
        }
    });

    // Camembert répartition candidatures
    new Chart(document.getElementById('pieChartTypes'), {
        type: 'doughnut',
        data: {
            labels: ['Offres', 'Spontanées'],
            datasets: [{
                data: [{{ $totalValideOffre }}, {{ $totalValideSpontanee }}],
                backgroundColor: ['#4e73df', '#1cc88a']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Barres horizontales : Top départements
    new Chart(document.getElementById('departementsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($topDepartements->pluck('departement.nom')) !!},
            datasets: [{
                label: 'Nombre de stagiaires',
                data: {!! json_encode($topDepartements->pluck('total')) !!},
                backgroundColor: '#39afd1'
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // Datepicker pour filtre (dépend de daterangepicker si intégré)
    $('#dash-daterange').daterangepicker({
        opens: 'left',
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: "Appliquer",
            cancelLabel: "Annuler",
            fromLabel: "De",
            toLabel: "À",
            daysOfWeek: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
            monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
            firstDay: 1
        }
    });

});
</script>
@endpush
