@extends('layouts.app')

@section('content')
 <div class="container mt-4">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('user.index') }}" class="btn btn-secondary"><i class="fe-arrow-left"></i></a>

                                <h4 class="page-title">Listes des rôles</h4>
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
                                                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>Ajouter un rôle
            </a>                             </div
                                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-striped" id="products-datatable">
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
                                                                <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-info" title="Details">
                                                                    <i class="fe-eye"></i> 
                                                                </a>
                                                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                                    <i class="mdi mdi-square-edit-outline"></i> 
                                                                </a>

                                                                <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer l\'utilisateur {{ addslashes($role->name) }} ?');">
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

@push('styles')
{{-- Pour que les icônes fonctionnent, assurez-vous d'inclure Font Awesome dans votre layout principal (layouts/app.blade.php) --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
@endpush
