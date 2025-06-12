@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Utilisation d'une "Card" Bootstrap pour un meilleur encadrement du contenu --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            {{-- Le titre de la page --}}
            <h1 class="h4 mb-0 text-primary fw-bold">
                <i class="fas fa-users me-2"></i>Details sur l'utilisateur
            </h1>
            {{-- Le bouton pour ajouter un utilisateur, aligné à droite --}}

        </div>

        <div class="card-body">

            {{-- Le div "table-responsive" assure que le tableau ne casse pas le design sur mobile --}}
            <div class="table-responsive">
                <ul>
                    <li>Nom: {{$user->name}}</li>
                    <li>Email: {{$user->email}}</li>
                    @foreach ($roles as $role)
                    <li>{{ $role->name }}</li>
                    @endforeach
                </ul>
            </div>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Pour que les icônes fonctionnent, assurez-vous d'inclure Font Awesome dans votre layout principal (layouts/app.blade.php) --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
@endpush
