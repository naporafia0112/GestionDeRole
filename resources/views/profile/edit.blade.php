@extends('layouts.home')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-body">
             <button type="button" class="btn-close" ><a href="{{ route('dashboard') }}" class=""><i class="fe-x-circle text-l"></i></a></button>
            <div class="d-flex align-items-center mb-4">
                <div class="me-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=100&background=4e54c8&color=fff" class="rounded-circle" alt="Avatar">
                </div>
                <div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    <small>Membre depuis le {{ $user->created_at->format('d/m/Y') }}</small>
                </div>
            </div>

            <hr>

            <h5 class="mt-4">Mon profil :</h5>
            <ul class="list-group mb-3">
                @forelse($user->roles as $role)
                    <li class="list-group-item">{{ $role->name }}</li>
                @empty
                    <li class="list-group-item text-muted">Aucun rôle attribué</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Font Awesome pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush
