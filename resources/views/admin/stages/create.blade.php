@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Titre et breadcrumb -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">DIPRH</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('offres.index') }}">Liste des offres</a></li>
                                        <li class="breadcrumb-item"><a href="">Liste des candidatures</a></li>
                                        <li class="breadcrumb-item active">Créer un Stage</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Créer un Stage</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire -->
                    <form id="stageForm" action="{{ route('stages.store') }}" method="POST">
                        @csrf
                        <div class="row">

                            <!-- Candidat concerné -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Candidat</label>
                               @if(isset($candidat) && $candidat)
                                    <input type="hidden" name="id_candidat" value="{{ $candidat->id }}">
                                    <input type="text" class="form-control" value="{{ $candidat->nom }} {{ $candidat->prenoms }}" disabled>
                                @else
                                    <select name="id_candidat" id="id_candidat" class="selectize-select">
                                        <option value="">-- Sélectionner --</option>
                                        @foreach($candidats as $c)
                                            <option value="{{ $c->id }}">{{ $c->nom }} {{ $c->prenoms }}</option>
                                        @endforeach
                                    </select>
                                @endif

                            </div>

                            <!-- Dates -->
                            <div class="col-lg-6 mb-3">
                                <label for="date_debut" class="form-label">Date début</label>
                                <input type="date" name="date_debut" id="date_debut" class="form-control">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="date_fin" class="form-label">Date fin</label>
                                <input type="date" name="date_fin" id="date_fin" class="form-control">
                            </div>

                            <!-- Sujet, lieu, département -->
                            <div class="col-lg-6 mb-3">
                                <label for="sujet" class="form-label">Sujet</label>
                                <input type="text" name="sujet" id="sujet" class="form-control">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="lieu" class="form-label">Lieu</label>
                                <input type="text" name="lieu" id="lieu" class="form-control" value="">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="departement" class="form-label">Département</label>
                                <input type="text" name="departement" id="departement" class="form-control">
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                                <a href="{{ route('stages.index') }}" class="btn btn-secondary">Retour</a>
                            </div>
                        </div>
                    </form>

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // Affichage des erreurs
        @if ($errors->any())
            let errorMessages = `{!! implode('<br>', $errors->all()) !!}`;
            Swal.fire({
                title: 'Erreurs de validation',
                html: errorMessages,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif

        // Confirmation avant soumission
        $('#stageForm').on('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirmation',
                text: "Souhaitez-vous créer ce stage sans tuteur ?",
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
