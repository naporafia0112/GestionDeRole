@extends('layouts.home')

@section('content')
<div class="container mt-4">
    {{-- Card Bootstrap --}}
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Breadcrumb et Titre --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Liste des utilisateurs</a></li>
                                <li class="breadcrumb-item active">Détails</li>
                            </ol>
                        </div>
                        <h4 class="page-title"><strong>Détails de l'utilisateur</strong></h4>
                    </div>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="mt-4">
                <p><strong>Nom :</strong> {{ $user->name }}</p>
                <p><strong>Email :</strong> {{ $user->email }}</p>
                <p><strong>Département :</strong> 
                    {{ $user->departement ? $user->departement->nom : 'Aucun département attribué' }}
                </p>

                <h5 class="mt-4"><strong>Rôles :</strong></h5>
                @forelse($roles as $role)
                    <span class="badge bg-primary">{{ $role->name }}</span>
                @empty
                    <p>Aucun rôle attribué</p>
                @endforelse

                <h5 class="mt-4"><strong>Permissions (héritées des rôles) :</strong></h5>
                @forelse($permissions as $permission)
                    <li>{{ $permission }}</li>
                @empty
                    <p>Aucune permission attribuée</p>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Si besoin d’icônes Font Awesome --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
@endpush
