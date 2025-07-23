@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h4>Exporter des données</h4>

    <form action="{{ route('rapport.generer') }}" method="GET" class="card p-4 shadow">
        @csrf
        <div class="mb-3">
            <label for="rapport_type" class="form-label">Type de données</label>
            <select name="rapport_type" id="rapport_type" class="form-select" required>
                <option value="">-- Sélectionnez --</option>
                <option value="candidats">Liste des candidats</option>
                <option value="stages">Liste des stages</option>
                <option value="candidatures">Candidatures acceptées</option>
            </select>
        </div>

        <div id="filtres_zone" class="filtres-container">
            <!-- Les filtres s'affichent dynamiquement -->
        </div>

        <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-file-export"></i> Exporter
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('rapport_type');
        const zone = document.getElementById('filtres_zone');

        function updateFilters() {
            const type = select.value;
            zone.innerHTML = '';

            if (type === 'candidats') {
                zone.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label">Type de dépôt</label>
                        <select name="type_depot" class="form-select">
                            <option value="">Tous</option>
                            <option value="stage academique">Académique</option>
                            <option value="stage professionnel">Professionnel</option>
                            <option value="stage de préembauche">Préembauche</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Période d'inscription</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="date" name="date_debut" class="form-control" placeholder="Date de début">
                            </div>
                            <div class="col-md-6">
                                <input type="date" name="date_fin" class="form-control" placeholder="Date de fin">
                            </div>
                        </div>
                    </div>
                `;
            } else if (type === 'stages') {
                zone.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label">Statut du stage</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                        </select>
                    </div>
                `;
            } else if (type === 'candidatures') {
                zone.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label">Type de dépôt</label>
                        <select name="type_depot" class="form-select">
                            <option value="">Tous</option>
                            <option value="stage academique">Académique</option>
                            <option value="stage professionnel">Professionnel</option>
                            <option value="stage de préembauche">Préembauche</option>
                        </select>
                    </div>
                `;
            }
        }

        select.addEventListener('change', updateFilters);

        // Si une valeur est pré-sélectionnée (ex: retour avec erreurs)
        if (select.value) {
            updateFilters();
        }
    });
</script>

{{-- SweetAlert messages --}}
@if(session('no_data'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Aucun résultat',
        text: '{{ session('no_data') }}',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: '{{ session('error') }}',
        confirmButtonText: 'OK'
    });
</script>
@endif
@endsection
