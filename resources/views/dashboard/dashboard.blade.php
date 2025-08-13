@extends('layouts.home')

@section('content')
<div class="container-fluid">

    <!-- Titre et filtre de date -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title">Dashboard Administrateur</h4>
            {{-- Filtre de date (optionnel) --}}
        </div>
    </div>

    <!-- Cartes Statistiques Principales (KPIs) -->
    <div class="row">
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-primary border-primary border me-3">
                        <i class="fe-users font-22 avatar-title text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="text-dark mb-1 display-5">{{ $countCandidats }}</h3>
                        <p class="text-muted mb-0">Total des Candidats</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-success border-success border me-3">
                        <i class="fe-file-text font-22 avatar-title text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="text-dark mb-1 display-5">{{ $countCandidatures }}</h3>
                        <p class="text-muted mb-0">Total des Candidatures</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-soft-info border-info border me-3">
                        <i class="fe-briefcase font-22 avatar-title text-info"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="text-dark mb-1 display-5">{{ $countUtilisateurs }}</h3>
                        <p class="text-muted mb-0">Total des Utilisateurs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Activité Mensuelle -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Activité mensuelle : Candidatures reçues</h5>
                    <canvas id="lineCandidatures" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Offres publiées par mois</h5>
                    <canvas id="chartOffresPubliees" height="180"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs créés par mois</h5>
                    <canvas id="usersParMoisChart" height="180"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Activité des stages par mois</h5>
                    <canvas id="stagesActivityChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Répartition et Statuts -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Statut des candidatures</h5>
                    <canvas id="donutValidation" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Statut des entretiens</h5>
                    <canvas id="entretiensStatutChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Répartition des rôles utilisateurs</h5>
                    <canvas id="chartUsersRoles" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Analyse par Département -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs par département</h5>
                    <canvas id="usersParDepartementChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Tuteurs par département</h5>
                    <canvas id="tuteursParDepartementChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Stages par département</h5>
                    <canvas id="stagesParDepartementChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

     <!-- Bouton d'export des graphiques -->
    <div class="row mb-3">
        <div class="col-md-12">
            <form id="exportForm" method="POST" action="{{ route('graph.export') }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Exporter des graphiques</h5>
                        <p class="text-muted mb-2">Sélectionnez les graphiques à exporter :</p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="selected_graphs[]" value="lineCandidatures" id="check1">
                            <label class="form-check-label" for="check1">Candidatures reçues par mois</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="selected_graphs[]" value="chartOffresPubliees" id="check2">
                            <label class="form-check-label" for="check2">Offres publiées par mois</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="selected_graphs[]" value="usersParMoisChart" id="check3">
                            <label class="form-check-label" for="check3">Utilisateurs par mois</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="selected_graphs[]" value="stagesActivityChart" id="check4">
                            <label class="form-check-label" for="check4">Activité des stages</label>
                        </div>
                        <!-- Ajoute d'autres si besoin -->
                        <button type="submit" class="btn btn-danger mt-3">Exporter en PDF</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = {!! json_encode($chartLabels) !!};
    const chartColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#fd7e14', '#20c997', '#6f42c1'];

    // --- ACTIVITÉ MENSUELLE ---

    new Chart(document.getElementById('lineCandidatures'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'Offres', data: @json($chartDataOffres), borderColor: chartColors[0], backgroundColor: 'rgba(78, 115, 223, 0.1)', tension: 0.3, fill: true },
                { label: 'Spontanées', data: @json($chartDataSpontanees), borderColor: chartColors[1], backgroundColor: 'rgba(28, 200, 138, 0.1)', tension: 0.3, fill: true }
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('chartOffresPubliees'), {
        type: 'bar',
        data: { labels: labels, datasets: [{ label: 'Offres publiées', data: @json($chartOffresPubliees), backgroundColor: chartColors[6] }] },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('usersParMoisChart'), {
        type: 'bar',
        data: { labels: labels, datasets: [{ label: 'Utilisateurs créés', data: @json($usersParMois), backgroundColor: chartColors[2] }] },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('stagesActivityChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Stages en cours', data: @json($stagesEnCours), backgroundColor: chartColors[8] },
                { label: 'Stages terminés', data: @json($stagesTermines), backgroundColor: chartColors[9] }
            ]
        },
        options: { plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true }, x: { stacked: true } }, interaction: { mode: 'index' } }
    });

    // --- RÉPARTITION ET STATUTS ---

    new Chart(document.getElementById('donutValidation'), {
        type: 'doughnut',
        data: {
            labels: ['Validées', 'Rejetées', 'Retenues', 'Reçues', 'En cours'],
            datasets: [{ data: [{{ $valide }}, {{ $rejete }}, {{ $retenu }}, {{ $reçue }},{{ $en_cours }}], backgroundColor: [chartColors[1], chartColors[4], chartColors[3], chartColors[2], chartColors[5]] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('entretiensStatutChart'), {
        type: 'doughnut',
        data: {
            labels: @json($entretiensByStatut->keys()),
            datasets: [{ data: @json($entretiensByStatut->values()), backgroundColor: chartColors }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('chartUsersRoles'), {
        type: 'pie',
        data: {
            labels: @json($rolesLabels),
            datasets: [{ data: @json($rolesCounts), backgroundColor: chartColors }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // --- ANALYSE PAR DÉPARTEMENT ---

    new Chart(document.getElementById('usersParDepartementChart'), {
        type: 'bar',
        data: {
            labels: @json($departementLabels),
            datasets: [{ label: 'Utilisateurs', data: @json($departementCounts), backgroundColor: chartColors[0] }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('tuteursParDepartementChart'), {
        type: 'bar',
        data: {
            labels: @json($tuteursParDepartement->keys()),
            datasets: [{ label: 'Tuteurs', data: @json($tuteursParDepartement->values()), backgroundColor: chartColors[1] }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('stagesParDepartementChart'), {
        type: 'bar',
        data: {
            labels: @json($stagesParDepartement->keys()),
            datasets: [{ label: 'Stages', data: @json($stagesParDepartement->values()), backgroundColor: chartColors[2] }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
    });


    // Initialisation du Datepicker
    $('#dash-daterange').daterangepicker({
        opens: 'left',
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: "Appliquer", cancelLabel: "Annuler",
            fromLabel: "De", toLabel: "À",
            daysOfWeek: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
            monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
            firstDay: 1
        }
    });
});
</script>
<script>
    document.getElementById('exportForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const selected = Array.from(document.querySelectorAll('input[name="selected_graphs[]"]:checked')).map(input => input.value);
        const chartImages = [];

        selected.forEach(id => {
            const canvas = document.getElementById(id);
            if (canvas) {
                const img = canvas.toDataURL("image/png");
                chartImages.push({ id, image: img });
            }
        });

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'chart_images';
        input.value = JSON.stringify(chartImages);

        this.appendChild(input);
        this.submit();
    });

</script>

@endpush
