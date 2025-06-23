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
                                    <a href="{{ route('offres.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus me-1"></i> Créer une offre
                                    </a>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-striped" id="offres-datatable">
                                    <thead class="text-center">
                                        <tr>
                                            <th>Titre</th>
                                            <th>Département</th>
                                            <th>Date de publication</th>
                                            <th>Localisation</th>
                                            <th>Statut</th>
                                            <th style="width: 120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @forelse ($offres as $offre)
                                        <tr>
                                            <td>{{ $offre->titre }}</td>
                                            <td style="white-space: normal; word-break: break-word;">{{ $offre->departement }}</td>
                                            <td>{{ $offre->date_publication ? $offre->date_publication->format('d/m/Y') : 'Non publié' }}</td>
                                            <td>{{ $offre->localisation->pays ?? 'Non défini' }}</td>
                                            <td>
                                                @if($offre->est_publie)
                                                    <span class="badge bg-success">Publié</span>
                                                @else
                                                    <span class="badge bg-secondary">Brouillon</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                @if($offre->est_publie)
                                                    <a href="{{ route('offres.candidatures', $offre->id) }}" class="btn btn-sm me-1 btn-secondary" title="Voir les candidatures">
                                                        <i class="mdi mdi-account-multiple"></i>
                                                    </a>
                                                @endif
                                                    <a href="{{ route('offres.show', $offre) }}" class="btn btn-sm  me-1 btn-info" title="Détails">
                                                        <i class="fe-eye"></i>
                                                    </a>
                                                    <a href="{{ route('offres.edit', $offre->id) }}" class="btn btn-sm  me-1 btn-warning" title="Modifier">
                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                    </a>

                                                    @if(!$offre->est_publie)
                                                        <form action="{{route('offres.publish', $offre->id) }}" id="publish-offre-{{ $offre->id }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="button" class="btn btn-sm  me-1 btn-primary" onclick="confirmPublish({{ $offre->id }})" title="Publier">
                                                                <i class="mdi mdi-send"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <form id="delete-offre-{{ $offre->id }}" action="{{ route('offres.destroy', $offre->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm  me-1 btn-danger" onclick="confirmDelete({{ $offre->id }})" title="Supprimer">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Aucune offre trouvée.
                                            </td>
                                        </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                                <div class="d-flex mt-3">
                                    {{ $offres->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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

<style>
    .btn-group {
        display: flex;
        gap: 5px;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush
@endsection
