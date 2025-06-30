@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">DIPRH</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('stages.index') }}">Stages</a></li>
                                        <li class="breadcrumb-item active">Créer un Stage</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Créer un Stage</h4>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="stageForm" action="{{ route('stages.store') }}" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-lg-6 mb-3">
                                <label for="id_candidat" class="form-label">Candidat</label>
                                <select name="id_candidat" id="id_candidat" class="selectize-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($candidats as $candidat)
                                        <option value="{{ $candidat->id }}">{{ $candidat->nom }} {{ $candidat->prenom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="id_tuteur" class="form-label">Tuteur</label>
                                <select name="id_tuteur" id="id_tuteur" class="selectize-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($tuteurs as $tuteur)
                                        <option value="{{ $tuteur->id }}">{{ $tuteur->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="date_debut" class="form-label">Date début</label>
                                <input type="date" name="date_debut" id="date_debut" class="form-control" required>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="date_fin" class="form-label">Date fin</label>
                                <input type="date" name="date_fin" id="date_fin" class="form-control" required>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="sujet" class="form-label">Sujet</label>
                                <input type="text" name="sujet" id="sujet" class="form-control" required>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="lieu" class="form-label">Lieu</label>
                                <input type="text" name="lieu" id="lieu" class="form-control" required>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="departement" class="form-label">Département</label>
                                <input type="text" name="departement" id="departement" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                                <a href="{{ route('stages.index') }}" class="btn btn-secondary">Retour</a>
                            </div>
                        </div>
                    </form>

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialiser Selectize
        $('.selectize-select').selectize({
            create: false,
            sortField: 'text'
        });

        // Confirmation SweetAlert au submit
        $('#stageForm').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirmation',
                text: "Souhaitez-vous créer ce stage ?",
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
