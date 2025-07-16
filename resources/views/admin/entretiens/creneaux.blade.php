@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="container-fluid">

        <!-- En-tête -->
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-success">Créneaux disponibles</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('entretiens.calendrier') }}">Calendrier</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Créneaux</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <i class="mdi mdi-calendar-clock fs-2 text-success"></i>
                </div>
            </div>
        </div>

        <!-- Calendrier -->
        <div class="card shadow border-0">
            <div class="card-body">
                <div id="creneaux-container" class="d-flex flex-wrap gap-3 justify-content-start align-items-start">
                    <div class="text-muted">Chargement des créneaux...</div>
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

    $.get('{{ route("entretiens.slots") }}')
        .done(function (slots) {
            if (!slots.length) {
                container.html('<div class="alert alert-warning">Aucun créneau disponible.</div>');
                return;
            }

            let html = '';
            slots.forEach(slot => {
                html += `
                    <div class="card slot-card shadow-sm border-0 bg-success bg-opacity-10 text-success"
                        style="min-width: 180px; max-width: 200px; cursor:pointer; transition: transform 0.2s ease;"
                        data-date="${slot.date}" data-heure="${slot.heure}">
                        <div class="card-body text-center">
                            <h6 class="mb-1 fw-semibold">${slot.date}</h6>
                            <p class="mb-0">${slot.heure}</p>
                        </div>
                    </div>`;
            });

            container.html(html);

            // Interactions
            $('.slot-card').hover(
                function () { $(this).css('transform', 'scale(1.05)'); },
                function () { $(this).css('transform', 'scale(1)'); }
            );

            $('.slot-card').on('click', function () {
                const date = $(this).data('date');
                const heure = $(this).data('heure');
                const id_candidat = '{{ $id_candidat ?? '' }}';
                const id_offre = '{{ $id_offre ?? '' }}';
                let url = "{{ route('entretiens.create') }}?date=" + encodeURIComponent(date) + "&heure=" + encodeURIComponent(heure);
                if (id_candidat) url += "&id_candidat=" + encodeURIComponent(id_candidat);
                if (id_offre) url += "&id_offre=" + encodeURIComponent(id_offre);
                window.location.href = url;
            });
        })
        .fail(function () {
            container.html('<div class="alert alert-danger">Erreur lors du chargement des créneaux.</div>');
        });
});
</script>
@endpush
