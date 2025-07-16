@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Liste des rôles</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title"><strong>Liste des rôles</strong></h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>Ajouter un rôle
                                    </a>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-striped" id="roles-table">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th style="width: 85px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($roles as $role)
                                            <tr>
                                                <td>{{ $role->name }}</td>
                                                <td class="text-center">
                                                    <div class="d-inline-flex gap-2">
                                                        <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-info" title="Détails">
                                                            <i class="fe-eye"></i>
                                                        </a>
                                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                            <i class="mdi mdi-square-edit-outline"></i>
                                                        </a>

                                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline form-delete" data-name="{{ $role->name }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">
                                                    Aucun rôle trouvé.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive -->
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

            <!-- Permissions section -->
            <div class="container mt-5">
                <h3 class="mb-3">Liste des Permissions</h3>
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <!-- Bouton pour ouvrir le modal -->
                                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
                                        <i class="fas fa-plus me-1"></i>Ajouter une permission
                                    </button>

                                    <table class="table table-centered table-nowrap table-striped" id="permissions-table">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th style="width: 85px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($permissions as $permission)
                                                <tr>
                                                    <td>{{ $permission->name }}</td>
                                                    <td class="text-center">
                                                        <div class="d-inline-flex gap-2">
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning btn-edit-permission"
                                                                data-id="{{ $permission->id }}"
                                                                data-name="{{ $permission->name }}"
                                                                data-url="{{ route('permissions.update', $permission) }}">
                                                                <i class="mdi mdi-square-edit-outline"></i>
                                                            </button>


                                                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline form-delete" data-name="{{ $permission->name }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                                    <i class="fas fa-trash-alt"></i>
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
                </div> <!-- end table-responsive -->
            </div> <!-- end container permissions -->

        </div> <!-- end container-fluid -->
    </div> <!-- end content -->
</div> <!-- end container -->

<!-- Modal création permission -->
<div class="modal fade" id="createPermissionModal" tabindex="-1" aria-labelledby="createPermissionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('permissions.store') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPermissionModalLabel">Créer une nouvelle permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="permissionName" class="form-label">Nom de la permission</label>
                    <input type="text" name="name" class="form-control" id="permissionName" placeholder="ex: creer_offre">
                </div>
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </div>
    </form>
  </div>
</div>
<!-- Modal modification permission -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editPermissionForm">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPermissionModalLabel">Modifier la permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editPermissionId">
                <div class="mb-3">
                    <label for="editPermissionName" class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" id="editPermissionName">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-success">Enregistrer</button>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
@endpush

@push('scripts')
    <!-- jQuery (requis par DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialisation des DataTables
            $('#roles-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });

            $('#permissions-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });

            // Événements pour les boutons de modification de permission
            document.querySelectorAll('.btn-edit-permission').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    const name = button.dataset.name;
                    const url = button.dataset.url;

                    document.getElementById('editPermissionName').value = name;
                    document.getElementById('editPermissionForm').action = url;

                    const modal = new bootstrap.Modal(document.getElementById('editPermissionModal'));
                    modal.show();
                });
            });

            // SweetAlert pour les messages flash
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: @json(session('success')),
                    confirmButtonColor: '#3085d6'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: @json(session('error')),
                    confirmButtonColor: '#d33'
                });
            @endif
            // Confirmation SweetAlert avant suppression
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Empêche l'envoi du formulaire immédiat

                    const permissionName = this.dataset.name;
                    const roleName = this.dataset.name;
                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: `La suppression de "${permissionName}" est irréversible.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit(); // Envoie le formulaire si confirmé
                        }
                    });
                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: `La suppression de "${roleName}" est irréversible.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit(); // Envoie le formulaire si confirmé
                        }
                    });
                });
            });

        });
    </script>
@endpush
