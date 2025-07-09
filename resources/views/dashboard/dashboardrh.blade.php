@extends('layouts.home')

@section('content')
<div class="container-fluid">

    <!-- Titre de la page -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Dashboard RH</h4>
                <form class="d-flex align-items-center mb-3">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control border" id="dash-daterange">
                        <span class="input-group-text bg-blue border-blue text-white">
                            <i class="mdi mdi-calendar-range"></i>
                        </span>
                    </div>
                    <a href="javascript: void(0);" class="btn btn-blue btn-sm ms-2">
                        <i class="mdi mdi-autorenew"></i>
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Cartes -->
    <div class="row">
        <!-- En attente -->
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-warning border-warning border me-3">
                        <i class="fe-clock font-22 avatar-title text-warning"></i>
                    </div>
                    <div class="flex-grow-1 text-end">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $countEnAttente }}</span></h3>
                        <p class="text-muted mb-1">Stages en attente</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- En cours -->
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-success border-success border me-3">
                        <i class="fe-check-circle font-22 avatar-title text-success"></i>
                    </div>
                    <div class="flex-grow-1 text-end">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $countEnCours }}</span></h3>
                        <p class="text-muted mb-1">Stages en cours</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Candidats -->
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-info border-info border me-3">
                        <i class="fe-users font-22 avatar-title text-info"></i>
                    </div>
                    <div class="flex-grow-1 text-end">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $countCandidats }}</span></h3>
                        <p class="text-muted mb-1">Candidats</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique donut -->
    <div class="row mt-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Statut des stages</h4>
                    <div id="stages-status-chart" class="apex-charts" data-colors="#f7b84b,#0acf97,#727cf5"></div>

                    <div class="row text-center mt-3">
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
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique Line Chart -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-3">Évolution des candidatures</h5>
            <canvas id="candidaturesChart" height="100"></canvas>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Animation des compteurs
    $('[data-plugin="counterup"]').counterUp({
        delay: 100,
        time: 1200
    });

    // Donut Chart
    const donutOptions = {
        series: [{{ $countEnAttente }}, {{ $countEnCours }}, {{ $countTermines }}],
        chart: {
            type: 'donut',
            height: 250,
        },
        labels: ["En attente", "En cours", "Terminés"],
        colors: ["#f7b84b", "#0acf97", "#727cf5"],
        legend: {
            show: false
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: { width: 200 },
                legend: { position: 'bottom' }
            }
        }]
    };
    new ApexCharts(document.querySelector("#stages-status-chart"), donutOptions).render();

    // Line Chart des candidatures
    const ctx = document.getElementById('candidaturesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Candidatures par mois',
                data: @json($chartData),
                fill: false,
                borderColor: '#4e73df',
                backgroundColor: '#4e73df',
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#4e73df',
                pointBorderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Nombre' }
                },
                x: {
                    title: { display: true, text: 'Mois' }
                }
            }
        }
    });

    // Date range picker
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
