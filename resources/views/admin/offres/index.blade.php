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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('offres.index') }}">Liste des offres</a></li>
                            </ol>
                        </div>
                        <h2 class="page-title">
                            <strong>Liste des offres</strong>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <!-- Bloc des boutons -->
                    <div class="col-lg-12 col-md-6 mb-2"> <!-- Réduction de mb-4 à mb-2 -->
                        <div class="card" style="border: 2px solid #dee2e6;">
                            <div class="card-body py-2"> <!-- Réduction du padding vertical -->
                                <h5 class="mb-3">Description des boutons</h5>
                                <div class="button-list">
                                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-send"></i> => Publié
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light">
                                        <i class="mdi mdi-account-multiple"></i> => Candidatures
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info waves-effect waves-light">
                                        <i class="fe-eye"></i> => Voir détails
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning waves-effect waves-light">
                                        <i class="mdi mdi-square-edit-outline"></i> => Modifier
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger waves-effect waves-light">
                                        <i class="mdi mdi-delete"></i> => Supprimer
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light">
                                        <i data-feather="plus-circle" class="icon-dual"></i> => Voir tous les boutons disponibles
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <a href="{{ route('offres.create') }}" class="btn btn-success mb-2">
                        <i class="fas fa-plus me-1"></i> Créer une offre
                    </a>
                </div>
                <div class="col-sm-8 text-sm-end">
                    <select id="statut-filter" class="form-select w-auto d-inline-block">
                        <option value="">Tous les statuts</option>
                        <option value="Publié">Publié</option>
                        <option value="Brouillon">Brouillon</option>
                    </select>
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
                        @foreach ($offres as $offre)
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
                                        <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-sm btn-info" title="Détails">
                                            <i class="fe-eye"></i>
                                        </a>
                                        <a href="{{ route('offres.edit', $offre->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </a>
                                        @if(!$offre->est_publie)
                                            <form action="{{ route('offres.publish', $offre->id) }}" id="publish-offre-{{ $offre->id }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-primary" onclick="confirmPublish({{ $offre->id }})" title="Publier">
                                                    <i class="mdi mdi-send"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form id="delete-offre-{{ $offre->id }}" action="{{ route('offres.destroy', $offre->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $offre->id }})" title="Supprimer">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
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
    .d-flex.gap-1 {
        gap: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
    $(document).ready(function() {
        const table = $('#offres-datatable').DataTable({
            pageLength: 5,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
            order: [[2, 'desc']],
            responsive: true
        });

        $('#statut-filter').on('change', function () {
            const val = $(this).val();
            table.column(4).search(val).draw();
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
