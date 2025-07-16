@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <!-- Carte principale -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Liste des rôles</a></li>
                                <li class="breadcrumb-item active">Details rôle</li>
                            </ol>
                        </div>
                        <h3 class="mb-4">
                            <strong>Détails du rôle :</strong> {{ $role->name }}
                        </h3>
                    </div>
                </div>
            </div>
            
            <!-- Permissions -->
            <div class="mb-4">
                <h5 class="text-primary mb-3">Permissions héritées :</h5>
                @forelse($permissions as $perm)
                    <span class="badge bg-info text-dark me-2 mb-2">
                        <i class="fas fa-key me-1"></i> {{ $perm->name }}
                    </span>
                @empty
                    <p class="text-muted">Ce rôle n’a actuellement aucune permission attribuée.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Font Awesome (icônes) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush
