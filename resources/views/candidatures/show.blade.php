@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h2>Détail candidature #{{ $candidature->id }}</h2>

    <div class="card mt-3 p-3">
        <h4>Informations candidat</h4>
        <p><strong>Nom :</strong> {{ $candidature->candidat->nom }}</p>
        <p><strong>Prénoms :</strong> {{ $candidature->candidat->prenoms }}</p>
        <p><strong>Email :</strong> {{ $candidature->candidat->email }}</p>
        <p><strong>Téléphone :</strong> {{ $candidature->candidat->telephone ?? '-' }}</p>
        <p><strong>Quartier :</strong> {{ $candidature->candidat->quartier ?? '-' }}</p>
        <p><strong>Ville :</strong> {{ $candidature->candidat->ville ?? '-' }}</p>
        <p><strong>Type de dépôt :</strong> {{ $candidature->candidat->type_depot ?? '-' }}</p>

        <h4 class="mt-4">Offre</h4>
        <p>{{ $candidature->offre->titre ?? '-' }}</p>

        <h4 class="mt-4">Fichiers</h4>
        <ul>
            @if($candidature->cv_fichier)
                <li>
                    <a href="{{ route('candidatures.download', ['id' => $candidature->id, 'f' => 'cv_fichier']) }}">Télécharger le CV</a>
                </li>
            @endif
            @if($candidature->lm_fichier)
                <li>
                    <a href="{{ route('candidatures.download', ['id' => $candidature->id, 'f' => 'lm_fichier']) }}" class="btn btn-sm btn-success">Télécharger lettre de motivation</a>
                </li>
            @endif
            @if($candidature->lr_fichier)
                <li>
                    <a href="{{ route('candidatures.download', ['id' => $candidature->id, 'f' => 'lr_fichier']) }}" class="btn btn-sm btn-warning">Télécharger lettre de recommandation</a>
                </li>
            @endif
        </ul>

        <h4 class="mt-4">Statut</h4>
        <p>{{ $candidature->statut }}</p>
    </div>

    <a href="{{ route('candidatures.index') }}" class="btn btn-secondary mt-3">Retour à la liste</a>
</div>
@endsection
