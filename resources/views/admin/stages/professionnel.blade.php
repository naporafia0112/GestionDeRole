@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="container-fluid">
             <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box">
                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">DIPRH</a></li>
                                                <li class="breadcrumb-item"><a href={{ route('offres.index') }}>Liste des entretiens</a></li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Liste des Stages accademiques</h4>
                                    </div>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table id="stages-datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Candidat</th>
                                            <th>Tuteur</th>
                                            <th>Date début</th>
                                            <th>Date fin</th>
                                            <th>Sujet</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stages as $stage)
                                            <tr>
                                                <td>{{ $stage->candidature->candidat->nom ?? '' }} {{ $stage->candidature->candidat->prenoms ?? '' }}</td>
                                                <td>{{ $stage->tuteur->name ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') }}</td>
                                                <td>{{ $stage->sujet }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $stage->statut)) }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('stages.show', $stage->id) }}" class="btn btn-sm btn-info" title="Détails">
                                                            <i class="fe-eye"></i>
                                                        </a>
                                                        <a href="{{ route('stages.edit', $stage->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                            <i class="mdi mdi-square-edit-outline"></i>
                                                        </a>
                                                        <form id="delete-stage-{{ $stage->id }}" action="{{ route('stages.destroy', $stage->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $stage->id }})" title="Supprimer">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">Aucun stage trouvé.</td>
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
<!-- DataTables js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#stages-datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        columnDefs: [
            { orderable: false, targets: 6 } // Colonne actions non triable
        ],
        order: [[2, 'desc']], // Tri par date début par défaut
        responsive: true,
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        pageLength: 10,
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Supprimer ce stage ?',
        text: "Cette action est irréversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-stage-' + id).submit();
        }
    });
}
</script>
@endpush

@push('styles')
<!-- DataTables css -->
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
    .d-flex.gap-1 {
        gap: 0.25rem;
    }
</style>
@endpush
@endsection
