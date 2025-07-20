@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <!-- Start Content-->
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
                                        <li class="breadcrumb-item active">Calendrier des entretiens</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Calendrier des entretiens</h4>
                            </div>
                        </div>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails Entretien -->
<!-- Modal Détails Entretien Modernisé -->
<div class="modal fade" id="entretienDetailModal" tabindex="-1" aria-labelledby="entretienDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient-primary text-white py-3">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="mdi mdi-calendar-account-outline me-2 fs-4"></i>
                    <span>Détails de l'entretien</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-4">
                <!-- En-tête résumé -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 id="modal-candidat-nom" class="mb-0 fw-bold text-dark">Chargement...</h4>
                        <small class="text-muted" id="modal-offre-titre">Chargement...</small>
                    </div>
                    <div>
                        <span class="badge rounded-pill bg-success text-dark border" id="modal-statut-badge">
                            <i class="mdi mdi-circle-medium me-1"></i><span id="modal-statut">Chargement...</span>
                        </span>
                    </div>
                </div>

                <!-- Infos principales -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded bg-light-subtle p-3 d-flex align-items-start h-100">
                            <div class="me-3 text-primary">
                                <i class="mdi mdi-calendar-clock fs-3"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Date & Heure</div>
                                <div class="fs-6 fw-semibold text-dark" id="modal-date-heure">Chargement...</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded bg-light-subtle p-3 d-flex align-items-start h-100">
                            <div class="me-3 text-primary">
                                <i class="mdi mdi-map-marker fs-3"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Lieu</div>
                                <div class="fs-6 fw-semibold text-dark" id="modal-lieu">Chargement...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Type entretien -->
                    <div class="col-md-6">
                        <div class="border rounded bg-light-subtle p-3 d-flex align-items-start h-100">
                            <div class="me-3 text-primary">
                                <i class="mdi mdi-account-tie fs-3"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Type d'entretien</div>
                                <div class="fs-6 fw-semibold text-dark" id="modal-type">Chargement...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Commentaire à côté -->
                    <div class="col-md-6">
                        <div class="border rounded bg-light-subtle p-3 d-flex align-items-start h-100">
                            <div class="me-3 text-primary">
                                <i class="mdi mdi-comment-text-outline fs-3"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Commentaire</div>
                                <div class="fs-6 fw-semibold text-dark" id="modal-commentaire">Aucun commentaire</div>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="modal-footer">
                <div class="d-flex gap-2">
                    <a id="btn-candidatures-entretien" href="#" class="btn btn-outline-secondary">
                        <i class="mdi mdi-account-multiple-outline me-1"></i> Voir candidatures
                    </a>
                    <button id="btn-cancel-entretien" class="btn btn-outline-danger">
                        <i class="mdi mdi-cancel me-1"></i> Annuler
                    </button>
                    <a id="btn-edit-entretien" href="#" class="btn btn-primary">
                        <i class="mdi mdi-pencil-outline me-1"></i> Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modification Entretien -->
<div class="modal fade" id="modalEditEntretien" tabindex="-1" aria-labelledby="modalEditEntretienLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="editEntretienForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'entretien</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_offre" id="edit-id_offre">
                    <input type="hidden" name="id_candidat" id="edit-id_candidat">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label>Date</label>
                            <input type="date" class="form-control" name="date" id="edit-date">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Heure</label>
                            <input type="time" class="form-control" name="heure" id="edit-heure">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Lieu</label>
                            <input type="text" class="form-control" name="lieu" id="edit-lieu">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Type</label>
                            <select class="form-control" name="type" id="edit-type">
                                @foreach(App\Models\Entretien::TYPES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Statut</label>
                           <select class="form-control" name="statut" id="edit-statut">
                                @foreach($statutsFiltres as $value => $label)
                                    @if(!in_array($value, ['en_cours', 'annule', 'termine']))
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>

                        </div>
                        <div class="col-12 mb-3">
                            <label>Commentaire</label>
                            <textarea class="form-control" name="commentaire" id="edit-commentaire" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#calendar').fullCalendar({
        editable: false,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        events: '{{ url("entretiens/events") }}',
        timeFormat: 'HH:mm',
        eventRender: function(event, element) {
            element.find('.fc-title').html(
                '<strong>' + event.title + '</strong><br>' +
                '<small>Statut: ' + event.statut + '</small>'
            );
        },
        eventClick: function(event) {
            $.ajax({
                url: '/entretiens/' + event.id + '/show-json',
                method: 'GET',
                success: function(data) {
                    $('#modal-candidat-nom').text(data.candidat || 'Inconnu');
                    $('#modal-offre-titre').text(data.offre || 'Candidature Spontanée');
                    $('#modal-date-heure').text(data.date + ' à ' + data.heure);
                    $('#modal-lieu').text(data.lieu || 'Non défini');
                    $('#modal-type').text(data.type || 'Non défini');
                    $('#modal-statut').text(data.statut || 'Non défini');
                    let couleurBadge = {
                        'Prévu': 'bg-primary',
                        'En cours': 'bg-warning text-dark',
                        'Effectué': 'bg-success text-white',
                        'Terminé': 'bg-teal text-white',
                        'Annulé': 'bg-danger text-white'
                    }[data.statut] || 'bg-secondary';

                    $('#modal-statut-badge')
                        .removeClass()
                        .addClass('badge rounded-pill border ' + couleurBadge)
                        .html('<i class="mdi mdi-circle-medium me-1"></i>' + data.statut);

                    $('#modal-commentaire').text(data.commentaire || 'Aucun commentaire');

                    const dateToSet = data.date || new Date().toISOString().split('T')[0];

                    $('#btn-edit-entretien').off('click').on('click', function () {
                        if (['Effectué', 'Annulé'].includes(data.statut)) {
                            Swal.fire('Action non autorisée', 'Seuls les entretiens effectués ou annulés peuvent être modifiés.', 'warning');
                            return;
                        }
                        $('#edit-date').val(dateToSet);
                        $('#edit-heure').val(data.heure);
                        $('#edit-lieu').val(data.lieu);
                        $('#edit-type').val(data.type);
                        $('#edit-statut').val(data.statut.toLowerCase());
                        $('#edit-commentaire').val(data.commentaire);
                        $('#edit-id_offre').val(data.id_offre);
                        $('#edit-id_candidat').val(data.id_candidat);
                        $('#editEntretienForm').attr('action', '/entretiens/' + event.id);
                        $('#modalEditEntretien').modal('show');
                    });

                    // Gérer le lien vers les candidatures
                    if (data.offre_id) {
                        const lienCandidature = '/offres/' + data.offre_id + '/candidatures';
                        $('#btn-candidatures-entretien')
                            .attr('href', lienCandidature)
                            .show();
                    } else {
                        $('#btn-candidatures-entretien').hide(); // Candidature spontanée
                    }



                    $('#btn-cancel-entretien').off('click').on('click', function () {
                        Swal.fire({
                            title: 'Voulez-vous annuler cet entretien ?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Oui, annuler',
                            cancelButtonText: 'Non',
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                        }).then((result) => {
                            if (result.isConfirmed) {
                               $.ajax({
                                    url: '/entretiens/' + event.id + '/annuler',
                                    type: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content'),
                                        _method: 'PATCH'
                                    },
                                    success: function () {
                                        $('#entretienDetailModal').modal('hide');
                                        $('#calendar').fullCalendar('refetchEvents');
                                        Swal.fire('Annulé!', 'L\'entretien a été annulé.', 'success');
                                    },
                                    error: function () {
                                        Swal.fire('Erreur', 'Impossible d\'annuler l\'entretien.', 'error');
                                    }
                                });
                            }
                        });
                    });

                    $('#entretienDetailModal').modal('show');
                },
                error: function() {
                    Swal.fire('Erreur', 'Impossible de charger les détails.', 'error');
                }
            });
        }
    });

    $('#editEntretienForm').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Confirmer la modification ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui',
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
