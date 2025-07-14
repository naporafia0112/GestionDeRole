@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="#">Stages en attente de tuteurs</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <strong>Liste des stages en attente de tuteurs</strong>
                        </h4>
                    </div>
                </div>
            </div>
            @if ($stages->isEmpty())
                <div class="alert alert-info">Aucun stage en attente de tuteur pour le moment.</div>
            @else
                <div class="table-responsive">
                    <table id="stages-attente-datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Candidat</th>
                                <th>Offre</th>
                                <th>Tuteur</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stages as $stage)
                                <tr>
                                    <td>{{ $stage->candidature->candidat->nom ?? '' }} {{ $stage->candidature->candidat->prenoms ?? $stage->candidature->candidat->prenom ?? '' }}</td>
                                    <td>{{ $stage->candidature->offre->titre ?? '---' }}</td>
                                    <td>{{ $stage->tuteur->name ?? '--' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') ?? '---' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') ?? '---' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#stages-attente-datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        responsive: true,
        pageLength: 10,
    });
});
</script>
@endpush

@push('styles')
<!-- DataTables css -->
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush
