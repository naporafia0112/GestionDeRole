@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="header-title mb-3">Affecter un tuteur au stage</h4>

                    <form id="affectationForm" class="needs-validation" novalidate method="POST" action="{{ route('stages.affecterTuteur', $stage->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Titre du stage :</label>
                            <input type="text" class="form-control" value="{{ $stage->titre ?? 'Stage #' . $stage->id }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="id_tuteur" class="form-label">Tuteur à affecter <span class="text-danger">*</span></label>
                            <select name="id_tuteur" id="id_tuteur" class="form-select" required>
                                <option value="">-- Sélectionner un tuteur --</option>
                                @foreach ($tuteurs as $tuteur)
                                    <option value="{{ $tuteur->id }}" {{ $stage->id_tuteur == $tuteur->id ? 'selected' : '' }}>
                                        {{ $tuteur->name }} ({{ $tuteur->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_tuteur')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">
                                Veuillez sélectionner un tuteur.
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('stages.index') }}" class="btn btn-outline-light">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fe-check-circle"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    (() => {
        'use strict'

        // Validation Bootstrap normale
        const form = document.querySelector('#affectationForm');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (form.checkValidity()) {
                // Formulaire valide, confirmation SweetAlert2
                Swal.fire({
                    title: 'Confirmer l\'affectation ?',
                    text: "Voulez-vous vraiment affecter ce tuteur au stage ?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, enregistrer',
                    cancelButtonText: 'Annuler',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                // Formulaire invalide, Bootstrap affiche les erreurs
                form.classList.add('was-validated');
            }
        }, false);
    })()
</script>
@endsection
