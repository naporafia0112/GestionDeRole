@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Titre et breadcrumb -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('offres.index') }}">Liste des offres</a></li>
                                        @if ($type === 'classique')
                                            <li class="breadcrumb-item"><a href="{{ route('offres.candidatures', $candidature->offre->id) }}">Liste des candidatures</a></li>
                                        @elseif ($type === 'spontanee')
                                            <li class="breadcrumb-item">Candidature spontanée</li> {{-- ou autre lien adapté --}}
                                        @endif
                                        <li class="breadcrumb-item active">Créer un Stage</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Créer un Stage</h4>
                            </div>
                        </div>
                    </div>
                    @if($type === 'classique' && $candidature->offre)
                        <span class="text-success"><p>Offre : {{ $candidature->offre->titre }}</p></span>
                    @elseif($type === 'spontanee')
                       <span class="text-success"> <p>Candidature spontanée</p></span>
                    @endif
                    <!-- Formulaire -->
                    <form id="stageForm" action="{{ route('stages.store') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        @if($type === 'classique')
                            <input type="hidden" name="id_candidature" value="{{ $candidature->id }}">
                        @else
                            <input type="hidden" name="id_candidature_spontanee" value="{{ $candidature->id }}">
                        @endif
                        <div class="row g-3">

                            <!-- Candidat -->
                            <div class="col-lg-6">
                                <label class="form-label">Candidat</label>
                                    @if($type === 'classique')
                                        <input type="hidden" name="id_candidature" value="{{ $candidature->id }}">
                                    @elseif($type === 'spontanee')
                                        <input type="hidden" name="id_candidature_spontanee" value="{{ $candidature->id }}">
                                    @endif
                                    <input type="text" class="form-control" disabled
                                        value="{{ $candidature->candidat->nom }} {{ $candidature->candidat->prenoms }}">
                            </div>

                            <!-- Sujet (modifiable) -->
                            <div class="col-lg-6">
                                <label for="sujet" class="form-label">Sujet du stage<span class="text-danger">*</span></label>
                                <input type="text" name="sujet" id="sujet" class="form-control @error('sujet') is-invalid @enderror"
                                    value="{{ old('sujet') }}" placeholder="Ex: Stage développement web">
                                @error('sujet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lieu (modifiable) -->
                            <div class="col-lg-6">
                                <label for="lieu" class="form-label">Lieu<span class="text-danger">*</span></label>
                                <input type="text" name="lieu" id="lieu" class="form-control @error('lieu') is-invalid @enderror"
                                    value="{{ old('lieu') }}" placeholder="Ex: Lomé, Togo">
                                @error('lieu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dates -->
                            <div class="col-lg-6">
                                <label for="date_debut" class="form-label">Date début<span class="text-danger">*</span></label>
                                <input type="date" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror"
                                    value="{{ old('date_debut') }}">
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label for="date_fin" class="form-label">Date fin<span class="text-danger">*</span></label>
                                <input type="date" name="date_fin" id="date_fin" class="form-control @error('date_fin') is-invalid @enderror"
                                    value="{{ old('date_fin') }}">
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Département (relationnel) -->
                            @if(isset($departements) && $departements->count())
                            <div class="col-lg-6">
                                <label for="id_departement" class="form-label">Département<span class="text-danger">*</span></label>
                                <select name="id_departement" id="id_departement" class="form-select @error('id_departement') is-invalid @enderror">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($departements as $dept)
                                        <option value="{{ $dept->id }}" {{ old('id_departement') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Rémunération -->
                            <div class="col-lg-6">
                                <label for="remuneration" class="form-label">Rémunération</label>
                                <input type="number" step="0.01" name="remuneration" class="form-control @error('remuneration') is-invalid @enderror"
                                    value="{{ old('remuneration') }}" placeholder="Ex: 150.00">
                                @error('remuneration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                                <a href="{{ route('offres.candidatures', $candidature->offre->id) }}" class="btn btn-secondary ms-2">Retour</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    @if ($errors->any())
        let errorMessages = `{!! implode('<br>', $errors->all()) !!}`;
        Swal.fire({
            title: 'Erreurs de validation',
            html: errorMessages,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    @endif

    $('#stageForm').on('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Confirmation',
            text: "Souhaitez-vous créer ce stage?",
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
});
</script>
@endpush
