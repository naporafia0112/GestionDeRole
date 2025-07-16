@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Breadcrumb + Titre -->
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('rh.stages.en_cours') }}">Liste des stages en cours</a></li>
                                        <li class="breadcrumb-item active">Modifier stage</li>
                                    </ol>
                                </div>
                                <h4 class="page-title"><strong>Modifier stage</strong></h4>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire -->
                    <form id="stageForm" action="{{ route('stages.update', $stage->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="rapport_stage_fichier" class="form-label">Rapport de stage (PDF)</label>
                            <input type="file" name="rapport_stage_fichier" class="form-control">
                            @if($stage->rapport_stage_fichier)
                                <p class="mt-2">
                                    <a href="{{ asset('storage/' . $stage->rapport_stage_fichier) }}" target="_blank">Voir le rapport actuel</a>
                                </p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="note_finale" class="form-label">Note finale (sur 20)</label>
                            <input type="number" name="note_finale" class="form-control" min="0" max="20" step="0.1" value="{{ old('note_finale', $stage->note_finale) }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('rh.stages.termines') }}" class="btn btn-light">Annuler</a>
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

        // Si erreurs de validation côté serveur
        @if ($errors->any())
            Swal.fire({
                title: 'Erreurs de validation',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif

        // Si succès envoyé via session (depuis le contrôleur)
        @if (session('success'))
            Swal.fire({
                title: 'Succès',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        // Confirmation avant soumission
        $('#stageForm').on('submit', function (e) {
            e.preventDefault(); // Empêche soumission directe
            Swal.fire({
                title: 'Confirmer la modification',
                text: "Souhaitez-vous enregistrer les modifications du stage ?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, enregistrer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Soumission réelle
                }
            });
        });
    });
</script>
@endpush
