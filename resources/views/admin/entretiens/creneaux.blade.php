@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="container-fluid">

            <!-- Calendrier -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                        <li class="breadcrumb-item active"><a href="{{ route('entretiens.calendrier') }}">Calendrier</a></li>
                                        <li class="breadcrumb-item active">Calendrier des crénaux</li>
                                    </ol>
                                </div>
                                <h2>Créneaux disponibles</h2>
                            </div>
                        </div>
                    </div>

                    <div id="creneaux-container" class="d-flex flex-wrap gap-2 mt-3" style="background-color: green">
                        Chargement des créneaux...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    const container = $('#creneaux-container');

    // Charger les créneaux
    $.get('{{ route("entretiens.slots") }}')
        .done(function (slots) {
            if (!slots.length) {
                container.html('<div class="text-danger">Aucun créneau disponible.</div>');
                return;
            }

            let html = '';
            slots.forEach(slot => {
                html += `<div class="card p-2 m-1" style="min-width: 150px; cursor:pointer;"
                            data-date="${slot.date}" data-heure="${slot.heure}">
                            <strong>${slot.date}</strong><br>${slot.heure}
                        </div>`;
            });

            container.html(html);

            // Après insertion HTML, on bind le clic sur les cartes
            $('#creneaux-container .card').on('click', function () {
                const date = $(this).data('date');
                const heure = $(this).data('heure');

                // Récupérer les variables passées depuis Blade
                const id_candidat = '{{ $id_candidat ?? '' }}';
                const id_offre = '{{ $id_offre ?? '' }}';

                // Construire l'URL avec query params
                let url = "{{ route('entretiens.create') }}?date=" + encodeURIComponent(date) + "&heure=" + encodeURIComponent(heure);
                if (id_candidat) url += "&id_candidat=" + encodeURIComponent(id_candidat);
                if (id_offre) url += "&id_offre=" + encodeURIComponent(id_offre);

                // Redirection
                window.location.href = url;
            });

        })
        .fail(function () {
            container.html('<div class="text-danger">Erreur lors du chargement des créneaux.</div>');
        });
});
</script>
@endpush
