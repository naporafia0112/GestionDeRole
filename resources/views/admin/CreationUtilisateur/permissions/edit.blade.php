@extends('layouts.home')

@section('content')
<div class="container">
    <h2>Modifier la Permission</h2>
    <form method="POST" action="{{ route('permissions.update', $permission) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" name="name" value="{{ $permission->name }}" class="form-control" >
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
    </form>
</div>
@endsection
