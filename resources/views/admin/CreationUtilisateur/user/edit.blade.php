@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="page-title"><strong>Modifier l'utilisateur</strong></h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">DIPRH</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item active">Modifier</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- Affichage des erreurs de validation --}}
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

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <h4 class="header-title">Mise à jour de l'utilisateur</h4>
                <form method="POST" action="{{ route('user.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Nom -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" >
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" >
                    </div>

                    <!-- Rôle -->
                    <div class="mb-3">
                        <label for="roles" class="form-label">Rôle</label>
                        <select name="roles[]" id="roles" class="form-select" >
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Champ Département ajouté -->
                    <div class="mb-3">
                        <label for="id_departement" class="form-label">Département</label>
                        <select id="id_departement" name="id_departement" class="form-select">
                            <option value="">-- Choisir un département --</option>
                            @foreach($departements as $departement)
                                <option value="{{ $departement->id }}" {{ old('id_departement') == $departement->id ? 'selected' : '' }}>
                                    {{ $departement->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
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
