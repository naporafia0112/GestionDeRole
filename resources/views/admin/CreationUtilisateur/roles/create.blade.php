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
                                <li class="breadcrumb-item"><a href="#">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Liste des rôles</a></li>
                                <li class="breadcrumb-item active">Créer un rôle</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Création d’un rôle</h4>
                    </div>
                </div>
            </div>

            {{-- Bloc message succès --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="header-title">Nouveau rôle</h4>

                            <form action="{{ route('roles.store') }}" method="POST">
                                @csrf

                                {{-- Nom du rôle --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom du rôle</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Ex: ADMIN, RH..." value="{{ old('name') }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Permissions --}}
                                <div class="mb-3">
                                    <label class="form-label">Permissions</label>
                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                           id="perm_{{ $permission->id }}"
                                                           class="form-check-input"
                                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('permissions') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary">Créer</button>
                                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Annuler</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert Success --}}
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif
@endsection
