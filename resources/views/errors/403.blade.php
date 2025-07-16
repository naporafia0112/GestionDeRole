@extends('layouts.home')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-4 text-danger">403 - Accès interdit</h1>
    <p class="lead">Vous n’avez pas l’autorisation d’accéder à cette page.</p>
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection
