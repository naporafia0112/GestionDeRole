@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h4>Générer un rapport</h4>

    <form action="{{ route('rapport.generer') }}" method="GET" class="card p-4 shadow">
        <div class="mb-3">
            <label for="rapport_type" class="form-label">Type de rapport</label>
            <select name="rapport_type" id="rapport_type" class="form-select" required>
                <option value="">-- Sélectionnez --</option>
                <option value="candidats">Liste des candidats</option>
                <option value="stages">Liste des stages</option>
                <option value="candidatures">Candidatures acceptées</option>
            </select>
        </div>

        <div id="filtres_zone"></div>

        <button type="submit" class="btn btn-success mt-3">Exporter</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('rapport_type');
        const zone = document.getElementById('filtres_zone');

        select.addEventListener('change', function () {
            const type = this.value;
            zone.innerHTML = '';

            if (type === 'candidats') {
                zone.innerHTML = `
                    <div class="mb-3">
                        <label>Type de stage</label>
                        <select name="type_stage" class="form-select">
                            <option value="">Tous</option>
                            <option value="academique">Académique</option>
                            <option value="professionnel">Professionnel</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label>Date de début</label>
                            <input type="date" name="date_debut" class="form-control">
                        </div>
                        <div class="col">
                            <label>Date de fin</label>
                            <input type="date" name="date_fin" class="form-control">
                        </div>
                    </div>
                `;
            }

            if (type === 'stages') {
                zone.innerHTML = `
                    <div class="mb-3">
                        <label>Statut du stage</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                        </select>
                    </div>
                `;
            }

            if (type === 'candidatures') {
                zone.innerHTML = `
                    <div class="mb-3">
                        <label>Type de stage</label>
                        <select name="type_stage" class="form-select">
                            <option value="">Tous</option>
                            <option value="academique">Académique</option>
                            <option value="professionnel">Professionnel</option>
                        </select>
                    </div>
                `;
            }
        });
    });
</script>
@endsection
