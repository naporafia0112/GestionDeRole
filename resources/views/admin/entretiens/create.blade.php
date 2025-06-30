@extends('layouts.home')

@section('content')
<div class="container">
    <h1 class="mb-4">Programmer un entretien</h1>

    {{-- Affichage des erreurs --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <form id="entretienForm" method="POST" action="{{ route('entretiens.store') }}">
        @csrf

        <div class="mb-3">
            <label for="date" class="form-label">Date :</label>
            <input type="date" name="date" id="date" class="form-control" required value="{{ old('date') }}">
        </div>

        <div class="mb-3">
            <label for="heure" class="form-label">Heure :</label>
            <input type="time" name="heure" id="heure" class="form-control" required value="{{ old('heure') }}">
        </div>

        <div class="mb-3">
            <label for="lieu" class="form-label">Lieu :</label>
            <input type="text" name="lieu" id="lieu" class="form-control" required value="{{ old('lieu') }}">
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type :</label>
            <input type="text" name="type" id="type" class="form-control" required placeholder="Présentiel / En ligne" value="{{ old('type') }}">
        </div>

        <div class="mb-3">
            <label for="statut" class="form-label">Statut :</label>
            <select name="statut" id="statut" class="form-select">
                <option value="">Choisir un statut</option>
                <option value="prévu" {{ old('statut') == 'prévu' ? 'selected' : '' }}>Prévu</option>
                <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="effectuée" {{ old('statut') == 'effectuée' ? 'selected' : '' }}>Effectuée</option>
                <option value="annulé" {{ old('statut') == 'annulé' ? 'selected' : '' }}>Annulé</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="commentaire" class="form-label">Commentaire :</label>
            <textarea name="commentaire" id="commentaire" class="form-control">{{ old('commentaire') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="id_candidat" class="form-label">Candidat :</label>
            <select name="id_candidat" id="id_candidat" class="form-select" required>
                <option value="" disabled selected>Choisir un candidat</option>
                @foreach($candidats as $candidat)
                    <option value="{{ $candidat->id }}" {{ old('id_candidat') == $candidat->id ? 'selected' : '' }}>
                        {{ $candidat->nom }} {{ $candidat->prenoms }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="id_offre" class="form-label">Offre :</label>
            <select name="id_offre" id="id_offre" class="form-select" required>
                <option value="" disabled selected>Choisir une offre</option>
                @foreach($offres as $offre)
                    <option value="{{ $offre->id }}" {{ old('id_offre') == $offre->id ? 'selected' : '' }}>
                        {{ $offre->titre }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success w-100">Créer l'entretien</button>
    </form>
</div>
@endsection

{{-- SWEETALERT AU SUBMIT --}}
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('entretienForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Confirmation',
            text: "Souhaitez-vous créer cet entretien ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, créer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
@endsection
