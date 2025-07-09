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
                                        <li class="breadcrumb-item"><a href="#">DIPRH</a></li>
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
<div class="modal fade" id="entretienDetailModal" tabindex="-1" aria-labelledby="entretienDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de l'entretien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4"><p class="text-muted">Candidat</p><h5 id="modal-candidat-nom">Chargement...</h5></div>
                    <div class="col-md-4"><p class="text-muted">Offre</p><h5 id="modal-offre-titre">Chargement...</h5></div>
                    <div class="col-md-4"><p class="text-muted">Date & Heure</p><h5 id="modal-date-heure">Chargement...</h5></div>
                    <div class="col-md-4 mt-3"><p class="text-muted">Lieu</p><h5 id="modal-lieu">Chargement...</h5></div>
                    <div class="col-md-4 mt-3"><p class="text-muted">Type</p><h5 id="modal-type">Chargement...</h5></div>
                    <div class="col-md-4 mt-3"><p class="text-muted">Statut</p><h5 id="modal-statut">Chargement...</h5></div>
                    <div class="col-md-12 mt-3">
                        <p class="text-muted">Commentaire</p>
                        <div class="card p-2 bg-light" id="modal-commentaire">Chargement...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a id="btn-edit-entretien" href="#" class="btn btn-success">Modifier</a>
                <a id="btn-candidatures-entretien" href="#" class="btn btn-ligth">Candidatures</a>
                <button id="btn-cancel-entretien" class="btn btn-danger">Annuler</button>
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
                        <div class="col-lg-6 mb-3">
                            <label>Statut</label>
                            <select class="form-control" name="statut" id="edit-statut">
                                @foreach($statutsFiltres as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
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
                    $('#modal-offre-titre').text(data.offre || 'Non précisé');
                    $('#modal-date-heure').text(data.date + ' à ' + data.heure);
                    $('#modal-lieu').text(data.lieu || 'Non défini');
                    $('#modal-type').text(data.type || 'Non défini');
                    $('#modal-statut').text(data.statut || 'Non défini');
                    $('#modal-commentaire').text(data.commentaire || 'Aucun commentaire');
                    
                    const dateToSet = data.date || new Date().toISOString().split('T')[0];

                    $('#btn-edit-entretien').off('click').on('click', function () {
                        $('#edit-date').val(dateToSet);
                        $('#edit-heure').val(data.heure);
                        $('#edit-lieu').val(data.lieu);
                        $('#edit-type').val(data.type);
                        $('#edit-statut').val(data.statut);
                        $('#edit-commentaire').val(data.commentaire);
                        $('#edit-id_offre').val(data.id_offre);
                        $('#edit-id_candidat').val(data.id_candidat);
                        $('#editEntretienForm').attr('action', '/entretiens/' + event.id);
                        $('#modalEditEntretien').modal('show');
                    });

                    $('#btn-candidatures-entretien').attr('href', '/offres/' + data.offre_id + '/candidatures');

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
                                    method: 'PATCH',
                                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
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
