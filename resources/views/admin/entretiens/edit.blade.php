@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h2>Modifier l'entretien</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('entretiens.update', $entretien->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $entretien->date) }}" required>
        </div>

        <div class="mb-3">
            <label for="heure" class="form-label">Heure</label>
            <input type="time" name="heure" id="heure" class="form-control" value="{{ old('heure', $entretien->heure) }}" required>
        </div>

        <div class="mb-3">
            <label for="lieu" class="form-label">Lieu</label>
            <input type="text" name="lieu" id="lieu" class="form-control" value="{{ old('lieu', $entretien->lieu) }}" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" name="type" id="type" class="form-control" value="{{ old('type', $entretien->type) }}" required>
        </div>

        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select name="statut" id="statut" class="form-select" required>
                @php
                    $statuts = ['prévu', 'en_cours', 'effectuée', 'annulé'];
                @endphp
                @foreach ($statuts as $statut)
                    <option value="{{ $statut }}" {{ old('statut', $entretien->statut) === $statut ? 'selected' : '' }}>{{ ucfirst($statut) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="commentaire" class="form-label">Commentaire</label>
            <textarea name="commentaire" id="commentaire" class="form-control">{{ old('commentaire', $entretien->commentaire) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="id_candidat" class="form-label">Candidat</label>
            <select name="id_candidat" id="id_candidat" class="form-select" required>
                @foreach ($candidats as $candidat)
                    <option value="{{ $candidat->id }}" {{ old('id_candidat', $entretien->id_candidat) == $candidat->id ? 'selected' : '' }}>
                        {{ $candidat->nom }} {{ $candidat->prenom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id_offre" class="form-label">Offre</label>
            <select name="id_offre" id="id_offre" class="form-select" required>
                @foreach ($offres as $offre)
                    <option value="{{ $offre->id }}" {{ old('id_offre', $entretien->id_offre) == $offre->id ? 'selected' : '' }}>
                        {{ $offre->titre }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="{{ route('entretiens.calendrier') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
