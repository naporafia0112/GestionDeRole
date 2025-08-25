@extends('layouts.home')

@section('content')
@php
    $labels = [
        'en_cours' => 'warning',
        'retenu' => 'success',
        'valide' => 'primary',
        'rejete' => 'danger',
        'effectuee' => 'info',
    ];
@endphp

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row mb-2">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('stages.index') }}">Liste des stages</a></li>
                                <li class="breadcrumb-item active">Stages en cours</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Stages en cours</h4>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table id="stages-datatable" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Candidat</th>
                            <th>Type de stage</th>
                            <th>Offre</th>
                            <th>Tuteur</th>
                            <th>Statut</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stagesParType as $typeDepot => $stages)
                            @foreach($stages as $stage)
                                @php
                                    $candidat = $stage->candidature->candidat ?? $stage->candidatureSpontanee->candidat ?? null;
                                @endphp
                                <tr data-type="{{ $typeDepot }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $candidat?->nom }} {{ $candidat?->prenoms }}</td>
                                    <td>{{ ucfirst($typeDepot) }}</td>
                                    <td>{{ $stage->candidature->offre->titre ?? '-' }}</td>
                                    <td>{{ $stage->tuteur->name ?? '-' }}</td>
                                    <td><span class="badge bg-{{ $labels[$stage->statut] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $stage->statut)) }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('stages.show', $stage->id) }}" class="btn btn-sm btn-info me-1" title="Voir les détails">
                                                <i class="fe-eye"></i>
                                            </a>
                                            @if($stage->statut === 'en_cours')
                                                <a href="{{ route('stages.edit', $stage->id) }}" class="btn btn-sm btn-warning me-1" title="Modifier le stage">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function () {
        const table = $('#stages-datatable').DataTable({
            responsive: true,
            order: [[6, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            columnDefs: [{ orderable: false, targets: [8] }]
        });

        // Filtres par type_depot
        $('#typeDepotTabs .nav-link').on('click', function () {
            $('#typeDepotTabs .nav-link').removeClass('active');
            $(this).addClass('active');
            const typeDepot = $(this).data('type');
            table.rows().every(function () {
                const row = $(this.node());
                row.toggle(!typeDepot || row.data('type') === typeDepot);
            });
        });
    });
</script>
@endpush
