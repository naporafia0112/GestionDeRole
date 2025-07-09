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
                                <li class="breadcrumb-item active">Modifier le rôle</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Modification d’un rôle</h4>
                    </div>
                </div>
            </div>

            <x-alert-success />

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="header-title">Éditer le rôle</h4>

                            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- Nom du rôle --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom du rôle</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $role->name) }}" >
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Permissions --}}
                                <div class="mb-3">
                                    <label class="form-label">Permissions</label>
                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" name="permissions[]"
                                                           value="{{ $permission->id }}"
                                                           id="perm_{{ $permission->id }}"
                                                           class="form-check-input"
                                                           {{ in_array($permission->id, old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}>
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
                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
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
