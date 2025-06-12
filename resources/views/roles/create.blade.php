@extends('layouts.app') {{-- ou ton layout principal --}}

@section('content')
<div class="container">
    <h2>Créer un rôle</h2>

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

        <div class="mb-3">
            <label for="name" class="form-label">Nom du rôle</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Permissions</label>
            <div class="form-check">
                @foreach ($permissions as $permission)
                    <div>
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input" id="perm_{{ $permission->id }}">
                        <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
@endsection
