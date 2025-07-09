@extends('layouts.home')

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
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
                <h4 class="page-title">Dashboard Directeur</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <!-- Card 1: Stages en attente -->
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                <i class="fe-clock font-22 avatar-title text-warning"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $countEnAttente }}</span></h3>
                                <p class="text-muted mb-1 ">Stages en attente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Stages en cours -->
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                <i class="fe-check-circle font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $countEnCours }}</span></h3>
                                <p class="text-muted mb-1 ">Stages en cours</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Candidats -->
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-users font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $countCandidats }}</span></h3>
                                <p class="text-muted mb-1 ">Candidats</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- end row -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mb-3">Statut des stages</h4>

                    <div dir="ltr">
                        <div id="stages-status-chart" class="apex-charts" data-colors="#f7b84b,#0acf97,#727cf5"></div>
                    </div>

                    <div class="row text-center mt-2">
                        <div class="col-4">
                            <p class="text-muted mb-1">En attente</p>
                            <h5 class="mt-0">{{ $countEnAttente }}</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted mb-1">En cours</p>
                            <h5 class="mt-0">{{ $countEnCours }}</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted mb-1">Terminés</p>
                            <h5 class="mt-0">{{ $countTermines }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

</div> <!-- container -->
@endsection

@section('scripts')
<!-- Apex Charts -->
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter animation
        $('[data-plugin="counterup"]').counterUp({
            delay: 100,
            time: 1200
        });

        // Stages Status Chart
        var options = {
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
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#stages-status-chart"), options);
        chart.render();

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
@endsection
