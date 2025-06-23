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
                                <li class="breadcrumb-item"><a href="javascript: void(0);">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route("roles.index") }}">Liste des rôles</a></li>
                                <li class="breadcrumb-item active">Créer un rôle</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Formulaire de création</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Ajout d'un nouveau rôle</h4>
                            <p class="sub-header">Définissez les permissions associées à ce rôle</p>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('roles.store') }}" method="POST">
                                @csrf

                                <!-- Champ Nom du rôle -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom du rôle</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Entrez le nom du rôle" value="{{ old('name') }}" required>
                                </div>

                                <!-- Champ Permissions -->
                                <div class="mb-3">
                                    <label class="form-label">Permissions</label>
                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" name="permissions[]"
                                                           value="{{ $permission->id }}"
                                                           class="form-check-input"
                                                           id="perm_{{ $permission->id }}"
                                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Boutons de soumission -->
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary">Créer le rôle</button>
                                    <a href="{{ route('roles.index') }}" class="btn btn-light">Annuler</a>
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
