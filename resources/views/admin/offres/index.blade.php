@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href={{ route('offres.index') }}>Liste des offres</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title">Liste des offres</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <a href="{{ route('offres.create') }}" class="btn btn-success mb-2">
                                        <i class="fas fa-plus me-1"></i> Créer une offre
                                    </a>
                                </div>
                                <div class="col-sm-8">
                                    <div class="text-sm-end">
                                        <!--button class="btn btn-light mb-2">Exporter</!--button-->
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="offres-datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Titre</th>
                                            <th>Département</th>
                                            <th>Date de publication</th>
                                            <th>Localisation</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($offres as $offre)
                                        <tr>
                                            <td>{{ $offre->titre }}</td>
                                            <td>{{ $offre->departement }}</td>
                                            <td>{{ $offre->date_publication ? $offre->date_publication->format('d/m/Y') : 'Non publié' }}</td>
                                            <td>{{ $offre->localisation->pays ?? 'Non défini' }}</td>
                                            <td>
                                                @if($offre->est_publie)
                                                    <span class="badge bg-success">Publié</span>
                                                @else
                                                    <span class="badge bg-secondary">Brouillon</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @if($offre->est_publie)
                                                        <a href="{{ route('offres.candidatures', $offre->id) }}" class="btn btn-sm btn-secondary" title="Candidatures">
                                                            <i class="mdi mdi-account-multiple"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('offres.show', $offre) }}" class="btn btn-sm btn-info" title="Détails">
                                                        <i class="fe-eye"></i>
                                                    </a>
                                                    <a href="{{ route('offres.edit', $offre->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                    </a>

                                                    @if(!$offre->est_publie)
                                                        <form action="{{route('offres.publish', $offre->id) }}" id="publish-offre-{{ $offre->id }}" method="POST">
                                                            @csrf
                                                            <button type="button" class="btn btn-sm btn-primary" onclick="confirmPublish({{ $offre->id }})" title="Publier">
                                                                <i class="mdi mdi-send"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <form id="delete-offre-{{ $offre->id }}" action="{{ route('offres.destroy', $offre->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $offre->id }})" title="Supprimer">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                Aucune offre trouvée.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#offres-datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            columnDefs: [
                { orderable: false, targets: 5 } // Désactiver le tri sur la colonne Actions
            ],
            order: [[2, 'desc']], // Tri par date de publication par défaut
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            pageLength: 10,
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Supprimer cette offre ?',
            text: "Cette action est irréversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-offre-' + id).submit();
            }
        });
    }

    function confirmPublish(id) {
        Swal.fire({
            title: 'Publier cette offre ?',
            text: "Elle sera visible publiquement.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, publier',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('publish-offre-' + id).submit();
            }
        });
    }
</script>
@endpush

@push('styles')
<!-- DataTables css -->
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet">

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

    .d-flex.gap-1 {
        gap: 0.25rem;
    }
</style>
@endpush
@endsection
