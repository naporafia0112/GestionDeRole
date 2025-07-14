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
                                            <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                            <li class="breadcrumb-item"><a href="">Liste des tuteurs</a></li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><strong>Liste des tuteurs</strong></h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->


                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                                                <thead>
                                                    <tr>
                                                        <th>Nom</th>
                                                        <th>Email</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($tuteurs as $tuteur)
                                                    <tr>
                                                        <td>{{ $tuteur->name }}</td>
                                                        <td>{{ $tuteur->email }}</td>
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

