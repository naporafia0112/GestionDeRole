@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="">Liste des candidatures retenus</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title">
                            Liste des candidatures en attente d'entretiens</strong>
                        </h4>
                    </div>
                </div>
    @if ($candidatures->isEmpty())
        <div class="alert alert-info">Aucun dossier retenu en attente de planification.</div>
    @else
        <div class="table-responsive">
            <table id="candidatures-datatable" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>Candidat</th>
                        <th>Offre</th>
                        <th>Date dépôt</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($candidatures as $candidature)
                        <tr>
                            <td>{{ $candidature->candidat->nom }} {{ $candidature->candidat->prenom }}</td>
                            <td>{{ $candidature->offre->titre ?? '---' }}</td>
                            <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                            @if($candidature->statut === 'retenu')
                                @if($candidature->entretien && $candidature->entretien->statut === 'effectuee')
                                    <form method="POST" action="{{ route('candidatures.valider', $candidature->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success confirm-validate">
                                            <i class="mdi mdi-check"></i> Valider
                                        </button>
                                    </form>
                                @else
                                    <!-- Debug : Affiche pourquoi le bouton n'apparaît pas -->
                                    <span class="badge bg-warning">
                                        @if(!$candidature->entretien)
                                            Pas d'entretien
                                        @else
                                            Statut: {{ $candidature->entretien->statut }}
                                        @endif
                                    </span>
                                @endif
                            @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
<style>
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 4px;
        padding: 5px 10px;
        border: 1px solid #dee2e6;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 4px;
        padding: 5px;
        border: 1px solid #dee2e6;
    }
    .table > :not(:first-child) {
        border-top: none;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<!-- DataTables JS -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
$(document).ready(function() {
    if (!$.fn.DataTable.isDataTable('#candidatures-datatable')) {
        $('#candidatures-datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json',
                search: "_INPUT_",
                searchPlaceholder: "Rechercher..."
            },
            responsive: true,
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: 3 }, // La colonne action non triable
                { className: 'text-center', targets: 3 }
            ],
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });
    }
});
</script>
@endpush
