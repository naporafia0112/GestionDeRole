@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row mb-2">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">DIPRH</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('rh.stages.en_cours') }}">Liste des stages en cours</a></li>
                                    <li class="breadcrumb-item"><a href="">Modifier stage</a></li>
                                </ol>
                            </div>
                            <h4 class="page-title">
                                <strong>Modifier stage</strong>
                            </h4>
                        </div>
                    </div>
                    <!-- Formulaire de modification -->
                    <form id="stageForm" action="{{ route('stages.update', $stage->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <!-- Candidat (non modifiable) -->
                            <div class="col-lg-6">
                                <label class="form-label">Candidat</label>
                                <input type="text" class="form-control" disabled
                                    value="{{ $stage->candidature->candidat->nom }} {{ $stage->candidature->candidat->prenoms }}">
                                <input type="hidden" name="id_candidature" value="{{ $stage->id_candidature }}">
                            </div>

                            <!-- Sujet -->
                            <div class="col-lg-6">
                                <label for="sujet" class="form-label">Sujet du stage<span class="text-danger">*</span></label>
                                <input type="text" name="sujet" id="sujet" class="form-control @error('sujet') is-invalid @enderror"
                                    value="{{ old('sujet', $stage->sujet) }}" placeholder="Ex: Stage développement web">
                                @error('sujet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lieu -->
                            <div class="col-lg-6">
                                <label for="lieu" class="form-label">Lieu<span class="text-danger">*</span></label>
                                <input type="text" name="lieu" id="lieu" class="form-control @error('lieu') is-invalid @enderror"
                                    value="{{ old('lieu', $stage->lieu) }}" placeholder="Ex: Lomé, Togo">
                                @error('lieu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date début -->
                            <div class="col-lg-6">
                                <label for="date_debut" class="form-label">Date début<span class="text-danger">*</span></label>
                                <input type="date" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror"
                                    value="{{ old('date_debut', $stage->date_debut->format('Y-m-d')) }}">
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date fin -->
                            <div class="col-lg-6">
                                <label for="date_fin" class="form-label">Date fin<span class="text-danger">*</span></label>
                                <input type="date" name="date_fin" id="date_fin" class="form-control @error('date_fin') is-invalid @enderror"
                                    value="{{ old('date_fin', $stage->date_fin->format('Y-m-d')) }}">
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Département -->
                            @if(isset($departements) && $departements->count())
                            <div class="col-lg-6">
                                <label for="id_departement" class="form-label">Département<span class="text-danger">*</span></label>
                                <select name="id_departement" id="id_departement" class="form-select @error('id_departement') is-invalid @enderror">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($departements as $dept)
                                        <option value="{{ $dept->id }}" {{ (old('id_departement', $stage->id_departement) == $dept->id) ? 'selected' : '' }}>
                                            {{ $dept->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Rémunération -->
                            <div class="col-lg-6">
                                <label for="remuneration" class="form-label">Rémunération</label>
                                <input type="number" step="0.01" name="remuneration" id="remuneration"
                                    class="form-control @error('remuneration') is-invalid @enderror"
                                    value="{{ old('remuneration', $stage->remuneration) }}" placeholder="Ex: 150.00">
                                @error('remuneration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-success">Mettre à jour</button>
                                <a href="{{ route('rh.stages.en_cours') }}" class="btn btn-secondary ms-2">Retour</a>
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
            text: "Souhaitez-vous enregistrer les modifications du stage ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, enregistrer',
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
