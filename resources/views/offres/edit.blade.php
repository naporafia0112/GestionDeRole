@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h2>Modifier une offre</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

<form action="{{ isset($offre) ? route('offres.update', $offre->id) : route('offres.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($offre))
        @method('PUT')
        @endif
        <div class="mb-3">
            <label for="titre">Titre *</label>
            <input type="text" name="titre" class="form-control"
                value="{{ old('titre', $offre->titre) }}" required>
        </div>

        <div class="mb-3">
            <label for="description">Description *</label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $offre->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="localisation_id">Localisation *</label>
            <select name="localisation_id" class="form-select" required>
                @foreach($localisations as $loc)
                    <option value="{{ $loc->id }}" {{ old('localisation_id', $offre->localisation_id) == $loc->id ? 'selected' : '' }}>
                        {{ $loc->pays }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="date_publication">Date de publication *</label>
            <input type="date" name="date_publication" class="form-control"
                value="{{ old('date_publication', $offre->date_publication->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label for="date_limite">Date limite *</label>
            <input type="date" name="date_limite" class="form-control"
                value="{{ old('date_limite', $offre->date_limite->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label for="exigences">Exigences *</label>
            <textarea name="exigences" class="form-control" rows="4" required>{{ old('exigences', $offre->exigences) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="departement">Département *</label>
            <input type="text" name="departement" class="form-control"
                value="{{ old('departement', $offre->departement) }}" required>
        </div>

        <div class="mb-3">
            <label for="fichier">Fichier PDF (optionnel)</label>
            @if($offre->fichier)
                <p>Fichier actuel : <a href="{{ asset('storage/' . $offre->fichier) }}" target="_blank">Voir</a></p>
            @endif
            <input type="file" name="fichier" class="form-control" accept="application/pdf">
        </div>

        <div class="mb-3">
            <input type="checkbox" name="est_publie" id="est_publie"
                {{ old('est_publie', $offre->est_publie) ? 'checked' : '' }}>
            <label for="est_publie">Publier cette offre</label>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('offres.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
