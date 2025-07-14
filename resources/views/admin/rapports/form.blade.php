@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h4>Générer un rapport</h4>

    <form action="{{ route('rapport.generer') }}" method="GET" class="card p-4 shadow">
        @csrf
        <div class="mb-3">
            <label for="rapport_type" class="form-label">Type de rapport</label>
            <select name="rapport_type" id="rapport_type" class="form-select" required>
                <option value="">-- Sélectionnez --</option>
                <option value="candidats">Liste des candidats</option>
                <option value="stages">Liste des stages</option>
                <option value="candidatures">Candidatures acceptées</option>
            </select>
        </div>

        <div id="filtres_zone" class="filtres-container">
            <!-- Les filtres vont apparaître ici dynamiquement -->
        </div>

        <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-file-export"></i> Exporter
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('rapport_type');
        const zone = document.getElementById('filtres_zone');

        function updateFilters() {
            const type = select.value;
            zone.innerHTML = '';

            if (type === 'candidats') {
                zone.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label">Type de stage</label>
                        <select name="type_stage" class="form-select">
                            <option value="">Tous</option>
                            <option value="academique">Académique</option>
                            <option value="professionnel">Professionnel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Période</label>
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
            }
            else if (type === 'stages') {
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
            }
            else if (type === 'candidatures') {
                zone.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label">Type de stage</label>
                        <select name="type_stage" class="form-select">
                            <option value="">Tous</option>
                            <option value="academique">Académique</option>
                            <option value="professionnel">Professionnel</option>
                        </select>
                    </div>
                `;
            }
        }

        // Attacher l'événement change
        select.addEventListener('change', updateFilters);

        // Afficher les filtres immédiatement si une valeur est déjà sélectionnée
        if (select.value) {
            updateFilters();
        }
    });
</script>
@endsection
