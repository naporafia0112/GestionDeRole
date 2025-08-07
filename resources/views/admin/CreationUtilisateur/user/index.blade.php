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
                                            <li class="breadcrumb-item"><a href="">DIPRH</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Liste des utilisateurs</a></li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><strong>Liste des utilisateurs</strong></h4>
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
                                                <a href="{{ route('user.create') }}" class="btn btn-success">
                                                <i class="fas fa-plus me-1"></i>Ajouter un utilisateur
            </a>                             </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                                                <thead>
                                                    <tr>
                                                        <th>Nom</th>
                                                        <th>Email</th>
                                                        <th>Statut</th>
                                                        <th>Département</th>
                                                        <th style="width: 85px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($users as $user)
                                                    <tr>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>
                                                            @if($user->active)
                                                                <span class="badge bg-success">Actif</span>
                                                            @else
                                                                <span class="badge bg-danger">Désactivé</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $user->departement ? $user->departement->nom : 'N/A' }}</td>
                                                        <td class="text-center">
                                                            <div class="col-sm-6 col-md-4 col-lg-3">
                                                                <div class="d-flex gap-1">
                                                                <a href="{{ route('user.show', $user) }}" class="btn btn-sm btn-info" title="Details">
                                                                    <i class="fe-eye"></i>
                                                                </a>
                                                           {{-- @if(auth()->user()->hasPermission('modifier_utilisateur'))  --}}
                                                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                           {{--@endif--}}
                                                                <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user) }}" method="POST" >
                                                                    @csrf
                                                                    @method('DELETE')
                                                                     <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $user->id }})" title="Supprimer">
                                                                        <i class="mdi mdi-delete"></i>
                                                                    </button>

                                                                </form>
                                                                <form id="toggle-active-form-{{ $user->id }}" action="{{ route('user.toggleActive', $user) }}" method="POST" style="display: inline;">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="button" class="btn btn-sm {{ $user->active ? 'btn-danger' : 'btn-success' }}"
                                                                        onclick="confirmToggleActive({{ $user->id }}, {{ $user->active ? 1 : 0 }})"
                                                                        title="{{ $user->active ? 'Désactiver' : 'Activer' }}">
                                                                        @if($user->active)
                                                                            <i class="mdi mdi-account-cancel"></i>
                                                                        @else
                                                                            <i class="mdi mdi-account-check"></i>
                                                                        @endif
                                                                    </button>
                                                                </form>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    {{-- Ce qui s'affiche si la liste d'utilisateurs est vide --}}
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted py-4">
                                                            Aucun utilisateur trouvé.
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

@endsection

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        const table = $('#offres-datatable').DataTable({
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
            title: 'Supprimer cet utilisateur ?',
            text: "Cette action est irréversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
    function confirmToggleActive(userId, isActive) {
        const actionText = isActive ? 'désactiver' : 'activer';
        const confirmButtonColor = isActive ? '#e3342f' : '#28a745';
        const icon = isActive ? 'warning' : 'info';

        Swal.fire({
            title: `Confirmer ${actionText} ?`,
            text: `Voulez-vous vraiment ${actionText} ce compte ?`,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Oui, ${actionText}`,
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('toggle-active-form-' + userId).submit();
            }
        });
    }
</script>

@endpush

@push('styles')
{{-- Pour que les icônes fonctionnent, assurez-vous d'inclure Font Awesome dans votre layout principal (layouts/app.blade.php) --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
@endpush
