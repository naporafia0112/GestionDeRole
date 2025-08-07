@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="mb-4">Nouvelle Attestation</h4>

                    {{-- Affichage des erreurs de validation --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('attestations.store') }}" method="POST">
                        @csrf

                        {{-- Candidat --}}
                        <div class="mb-3">
                            <label class="form-label">Nom et Prénoms du Candidat</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $selectedStage->candidature->candidat->nom }} {{ $selectedStage->candidature->candidat->prenoms }}">
                        </div>

                        {{-- ID du Stage (champ caché) --}}
                        <input type="hidden" name="stage_id" value="{{ $selectedStage->id }}">

                        {{-- Service --}}
                        <div class="mb-3">
                            <label for="service" class="form-label">Service</label>
                            <input type="text" name="service" id="service" class="form-control" required
                                value="{{ old('service', 'CAGECFI SA, 03 BP 31041, Téléphone : 22 26 84 61, Lomé – Togo') }}">
                        </div>

                        {{-- Date de début --}}
                        <div class="mb-3">
                            <label for="debut" class="form-label">Date de début</label>
                            <input type="date" name="debut" id="debut" class="form-control" required
                                value="{{ old('debut', optional($selectedStage->date_debut)->format('Y-m-d')) }}">
                        </div>

                        {{-- Date de fin --}}
                        <div class="mb-3">
                            <label for="fin" class="form-label">Date de fin</label>
                            <input type="date" name="fin" id="fin" class="form-control" required
                                value="{{ old('fin', optional($selectedStage->date_fin)->format('Y-m-d')) }}">
                        </div>

                        {{-- Type de stage --}}
                        <div class="mb-3">
                            <label for="type" class="form-label">Type de Stage</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="academique" {{ old('type', $selectedStage->type) === 'academique' ? 'selected' : '' }}>Académique</option>
                                <option value="professionnel" {{ old('type', $selectedStage->type) === 'professionnel' ? 'selected' : '' }}>Professionnel</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Générer l'attestation</button>
                            <a href="{{ route('attestations.liste') }}" class="btn btn-light ms-2">Annuler</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
