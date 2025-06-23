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
                                                        <th style="width: 85px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($users as $user)
                                                    <tr>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td class="text-center">
                                                            <div class="col-sm-6 col-md-4 col-lg-3">
                                                                <a href="{{ route('user.show', $user) }}" class="btn btn-sm btn-info" title="Details">
                                                                    <i class="fe-eye"></i>
                                                                </a>
                                                           {{-- @if(auth()->user()->hasPermission('modifier_utilisateur'))  --}}
                                                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                           {{--@endif--}}

                                                                <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-danger"
                                                                title="Supprimer"
                                                                onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) { document.getElementById('delete-form-{{ $user->id }}').submit(); }">
                                                                <i class="mdi mdi-delete"></i>
                                                                </a>

                                                                <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user) }}" method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
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
