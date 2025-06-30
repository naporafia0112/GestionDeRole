@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="">DIPRH</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Liste des rôle</a></li>
                                            <li class="breadcrumb-item active"><strong>Détails rôles</strong></li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><strong>Détails de {{$role->name}}</strong></h4>
                                    <h4>Permissions héritées :</h4>
                                        <ul>
                                            @foreach($permissions as $perm)
                                                <li>{{ $perm }}</li>
                                            @endforeach
                                        </ul>
                                </div>
                            </div>
                        </div>


        <div class="card-body">

            <div class="table-responsive">
                <ul>
                        @forelse ($permissions as $permission)
                            <li>{{ $permission->name }}</li>
                        @empty
                            <li>Aucune permission attribuée</li>
                        @endforelse
                    </ul>
                </li>
            </ul>
            </div>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Pour que les icônes fonctionnent, assurez-vous d'inclure Font Awesome dans votre layout principal (layouts/app.blade.php) --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
@endpush
