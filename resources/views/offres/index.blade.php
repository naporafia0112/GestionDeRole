@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <h4 class="page-title">Liste des offres</h4>
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
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Département</th>
                                            <th>Date de publication</th>
                                            <th>Localisation</th>
                                            <th>Statut</th>
                                            <th style="width: 120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                                    <a href="{{ route('offres.show', $offre) }}" class="btn btn-sm btn-info" title="Détails">
                                                        <i class="fe-eye"></i>
                                                    </a>
                                                    <a href="{{ route('offres.edit', $offre->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                    </a>

                                                    @if(!$offre->est_publie)
                                                        <form action="{{route('offres.publish', $offre->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-primary" title="Publier">
                                                                <i class="mdi mdi-send"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <form action="{{ route('offres.destroy', $offre->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')" title="Supprimer">
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
@endsection

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
