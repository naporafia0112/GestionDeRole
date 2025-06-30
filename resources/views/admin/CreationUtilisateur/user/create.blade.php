@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Liste des utilisateurs</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title"><strong>Créer un utilisateur</strong></h4>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Erreur(s) :</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="header-title">Ajout d'un nouvel utilisateur</h4>
                            <p class="sub-header">Remplissez les informations de base</p>

                            <form action="{{ route('user.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmation mot de passe</label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>

                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Rôle</label>
                                    <select id="role_id" name="role_id" class="form-select" required>
                                        <option value="">Sélectionnez un rôle...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">Créer</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-light">Annuler</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6'
    });
</script>
@endif
@endpush
