@extends('layouts.home')

@section('content')
<div class="container">
    <h2>Détails de l'entretien</h2>
    <div class="col-auto">
        <a href="{{ route('entretiens.index') }}" class="btn btn-sm btn-link">
        <i class="mdi mdi-keyboard-backspace"></i> Retour
        </a>
    </div>
    <ul>
        <li><strong>Date :</strong> {{ $entretien->date }}</li>
        <li><strong>Heure :</strong> {{ $entretien->heure }}</li>
        <li><strong>Lieu :</strong> {{ $entretien->lieu }}</li>
        <li><strong>Candidat :</strong> {{ $entretien->candidat->nom }}</li>
        <li><strong>Offre :</strong> {{ $entretien->offre->titre }}</li>
    </ul>
</div>
@endsection
