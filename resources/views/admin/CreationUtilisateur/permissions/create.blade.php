@extends('layouts.home')

@section('content')
<div class="container">
    <h2>Créer une Permission</h2>
    <form method="POST" action="{{ route('permissions.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nom<span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
@endsection
