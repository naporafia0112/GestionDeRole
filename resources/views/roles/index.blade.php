@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Utilisation d'une "Card" Bootstrap pour un meilleur encadrement du contenu --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            {{-- Le titre de la page --}}
            <h1 class="h4 mb-0 text-primary fw-bold">
                <i class="fas fa-users me-2"></i>Liste des Roles
            </h1>
            {{-- Le bouton pour ajouter un utilisateur, aligné à droite --}}
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Ajouter un role
            </a>
        </div>

        <div class="card-body">
            {{-- Alerte de succès améliorée, qui peut être fermée par l'utilisateur --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Le div "table-responsive" assure que le tableau ne casse pas le design sur mobile --}}
            <div class="table-responsive">
                <a href="{{ route('user.index') }}" class="btn btn-secondary">Retour</a>
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @forelse est plus propre : il gère le cas où la liste est vide --}}
                        @forelse ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            {{-- Utilisation de Flexbox pour aligner proprement les boutons d'action --}}
                            <td class="text-center">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-info" title="Details">
                                        <i class="fas fa-pencil-alt"></i> Details
                                    </a>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-pencil-alt"></i> Modifier
                                    </a>

                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer l\'utilisateur {{ addslashes($role->name) }} ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i> Supprimer
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
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Pour que les icônes fonctionnent, assurez-vous d'inclure Font Awesome dans votre layout principal (layouts/app.blade.php) --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
@endpush
