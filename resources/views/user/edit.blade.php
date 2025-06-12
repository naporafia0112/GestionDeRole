@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier l’utilisateur</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('user.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label>Rôle</label>
            <select name="role_id" class="form-control" required>
                <option value="">-- Choisir un rôle --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
