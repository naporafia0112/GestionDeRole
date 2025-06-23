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
                                    <h4 class="page-title"><strong>Modifier</strong></h4>
                                </div>
                            </div>
                        </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Modification de l'utilisateur</h4>
                            <p class="sub-header">Modifiez les informations de l'utilisateur</p>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('user.update', $user) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Champ Nom complet -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Entrez le nom complet" value="{{ old('name', $user->name) }}" required>
                                </div>

                                <!-- Champ Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Entrez l'adresse email" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <!-- Champ Mot de passe (optionnel en édition) -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe (laisser vide si inchangé)</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Laissez vide pour conserver l'actuel">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirmation Mot de passe -->
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmation mot de passe</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" placeholder="Confirmez le nouveau mot de passe">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Champ Rôle avec Selectize -->
                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Rôle</label>
                                    <select id="role_id" name="role_id" class="selectize-select" required>
                                        <option value="">Sélectionnez un rôle...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ (old('role_id', $user->role_id) == $role->id ? 'selected' : '' )}}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Boutons de soumission -->
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-light">Annuler</a>
                                </div>
                            </form>

                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
        </div>
    </div>
</div>
@endsection
