@extends('layouts.home')

@section('content')
<div class="container-fluid">
    <!-- Cartes statistiques -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="fw-bold text-muted">Candidats</h5>
                    <h2 class="text-primary">{{ $countCandidats }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="fw-bold text-muted">Candidatures</h5>
                    <h2 class="text-success">{{ $countCandidatures }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="fw-bold text-muted">Utilisateurs</h5>
                    <h2 class="text-info">{{ $countUtilisateurs }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row g-3 mt-4">

        <!-- Graphique principal large -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Candidatures reçues par mois</h5>
                    <canvas id="lineCandidatures"></canvas>
                </div>
            </div>
        </div>
         <!-- Petit graphique tout en bas -->
        <div class="col-lg-6">
            <div class="card shadow-sm flex-fill">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Répartition des rôles</h5>
                    <canvas id="chartUsersRoles"></canvas>
                </div>
            </div>
        </div>
        <!-- Petits graphiques côte à côte -->
        <div class="col-lg-6">
            <div class="card shadow-sm flex-fill">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Types de dépôt</h5>
                    <canvas id="pieDepotType"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">    
            <div class="card shadow-sm flex-fill">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Validées vs Rejetées</h5>
                    <canvas id="donutValidation"></canvas>
                </div>
            </div>
        </div>

        <!-- Autres graphiques moyens -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Stages en cours par mois</h5>
                    <canvas id="barStagesEnCours"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Offres publiées par mois</h5>
                    <canvas id="chartOffresPubliees"></canvas>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($chartLabels) !!};

    // Candidatures reçues (grand)
    new Chart(document.getElementById('lineCandidatures'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Offres',
                    data: {!! json_encode($chartDataOffres) !!},
                    borderColor: '#4e73df',
                    backgroundColor: '#4e73df44',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Spontanées',
                    data: {!! json_encode($chartDataSpontanees) !!},
                    borderColor: '#1cc88a',
                    backgroundColor: '#1cc88a44',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Types de dépôt (petit)
    new Chart(document.getElementById('pieDepotType'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($typesDepot->keys()) !!},
            datasets: [{
                data: {!! json_encode($typesDepot->values()) !!},
                backgroundColor: ['#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Validation vs Rejets (petit)
    new Chart(document.getElementById('donutValidation'), {
        type: 'doughnut',
        data: {
            labels: ['Validées', 'Rejetées', 'Retenues'],
            datasets: [{
                data: [{{ $valide }}, {{ $rejete }}, {{ $retenu }}],
                backgroundColor: ['#1cc88a', '#e74a3b', '#f6c23e'],
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Stages en cours (moyen)
    new Chart(document.getElementById('barStagesEnCours'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Stages en cours',
                data: {!! json_encode($chartStagesEnCours) !!},
                backgroundColor: '#36b9cc'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Offres publiées (moyen)
    new Chart(document.getElementById('chartOffresPubliees'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Offres publiées',
                data: {!! json_encode($chartOffresPubliees) !!},
                backgroundColor: '#8e44ad'
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Répartition des rôles (petit)
    new Chart(document.getElementById('chartUsersRoles'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($rolesLabels) !!},
            datasets: [{
                data: {!! json_encode($rolesCounts) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc']
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush
