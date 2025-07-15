@extends('layouts.home')

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard Tuteur</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <!-- Candidats en stage -->
        <div class="col-md-6 col-xl-3">
            <div class="card widget-rounded-circle">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                <i class="fe-user-check font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <h3 class="text-dark mt-1">
                                <span data-plugin="counterup">{{ $countCandidatsEnCours }}</span>
                            </h3>
                            <p class="text-muted mb-1">Candidats en stage</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Candidats terminés -->
        <div class="col-md-6 col-xl-3">
            <div class="card widget-rounded-circle">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                <i class="fe-user-check font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <h3 class="text-dark mt-1">
                                <span data-plugin="counterup">{{ $countCandidatsTermines }}</span>
                            </h3>
                            <p class="text-muted mb-1">Candidats terminés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique Donut -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mb-3">Répartition des stages</h4>

                    <div dir="ltr">
                        <div id="tuteur-status-chart" class="apex-charts" data-colors="#0acf97,#727cf5"></div>
                    </div>

                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <p class="text-muted mb-1">En cours</p>
                            <h5 class="mt-0">{{ $countCandidatsEnCours }}</h5>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Terminés</p>
                            <h5 class="mt-0">{{ $countCandidatsTermines }}</h5>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div> <!-- end container -->
@endsection

@section('scripts')
<!-- Apex Charts -->
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Counter animation
        $('[data-plugin="counterup"]').counterUp({
            delay: 100,
            time: 1200
        });

        // Donut Chart
        var options = {
            series: [{{ $countCandidatsEnCours }}, {{ $countCandidatsTermines }}],
            chart: {
                type: 'donut',
                height: 250
            },
            labels: ["En cours", "Terminés"],
            colors: ["#0acf97", "#727cf5"],
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

        var chart = new ApexCharts(document.querySelector("#tuteur-status-chart"), options);
        chart.render();
    });
</script>
@endsection
