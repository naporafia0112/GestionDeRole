@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Calendrier des entretiens</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title">Calendrier des entretiens</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                   <a href="{{ route('entretiens.create') }}" class="btn btn-sm me-1 btn-outline-primary mb-3">
                                        <i class="mdi mdi-calendar-clock"></i> Planifier entretien
                                    </a>
                                </div>
                            </div>

                        <div id="calendar">
                    </div>
                    <!-- Modal Détails Entretien -->
<div class="modal fade" id="entretienDetailModal" tabindex="-1" aria-labelledby="entretienDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="entretienDetailModalLabel">Détails de l'entretien</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <p><strong>Titre :</strong> <span id="entretien-title"></span></p>
        <p><strong>Date :</strong> <span id="entretien-date"></span></p>
        <p><strong>Heure :</strong> <span id="entretien-time"></span></p>
        <p><strong>Lieu :</strong> <span id="entretien-lieu"></span></p>
        <p><strong>Type :</strong> <span id="entretien-type"></span></p>
        <p><strong>Statut :</strong> <span id="entretien-statut"></span></p>
        <p><strong>Commentaire :</strong> <span id="entretien-commentaire"></span></p>
      </div>
      <div class="modal-footer">
        <a href="#" id="btn-edit-entretien" class="btn btn-primary">Modifier</a>
        <button type="button" class="btn btn-danger" id="btn-cancel-entretien">Annuler</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

                </div>
            </div>
        </div>
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
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendar = $('#calendar').fullCalendar({
                editable: true,
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
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    var title = prompt('Titre de l\'entretien :');
                    if (title) {
                        var startFormatted = $.fullCalendar.formatDate(start, 'Y-MM-DD HH:mm');
                        var endFormatted = $.fullCalendar.formatDate(end, 'Y-MM-DD HH:mm');

                        $.ajax({
                            url: "{{ url('full-calender/action') }}",
                            type: "POST",
                            data: {
                                title: title,
                                start: startFormatted,
                                end: endFormatted,
                                type: 'add'
                            },
                            success: function(data) {
                                calendar.fullCalendar('refetchEvents');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Entretien ajouté',
                                    text: 'L\'entretien a été planifié avec succès.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                },
                eventResize: function(event) {
                    var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm');
                    var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm');

                    $.ajax({
                        url: "{{ url('full-calender/action') }}",
                        type: "POST",
                        data: {
                            title: event.title,
                            start: start,
                            end: end,
                            id: event.id,
                            type: 'update'
                        },
                        success: function(response) {
                            calendar.fullCalendar('refetchEvents');
                            Swal.fire({
                                icon: 'success',
                                title: 'Entretien modifié',
                                text: 'La durée a été modifiée avec succès.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                },
                eventDrop: function(event) {
                    var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm');
                    var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm');

                    $.ajax({
                        url: "{{ url('full-calender/action') }}",
                        type: "POST",
                        data: {
                            title: event.title,
                            start: start,
                            end: end,
                            id: event.id,
                            type: 'update'
                        },
                        success: function(response) {
                            calendar.fullCalendar('refetchEvents');
                            Swal.fire({
                                icon: 'info',
                                title: 'Entretien déplacé',
                                text: 'L\'horaire de l\'entretien a été modifié.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                },
               eventClick: function(event) {
    // Appel AJAX pour récupérer les détails d'un entretien
    $.ajax({
        url: '/entretiens/' + event.id + '/show-json', // Crée cette route pour renvoyer JSON
        method: 'GET',
        success: function(data) {
            // Remplir le modal avec les données
            $('#entretien-title').text(data.title);
            $('#entretien-date').text(data.date);
            $('#entretien-time').text(data.heure);
            $('#entretien-lieu').text(data.lieu);
            $('#entretien-type').text(data.type);
            $('#entretien-statut').text(data.statut);
            $('#entretien-commentaire').text(data.commentaire || 'Aucun');

            // Modifier lien modifier
            $('#btn-edit-entretien').attr('href', '/entretiens/' + event.id + '/edit');

            // Gérer bouton annuler entretien
            $('#btn-cancel-entretien').off('click').on('click', function() {
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
                            data: {_token: $('meta[name="csrf-token"]').attr('content')},
                            success: function() {
                                $('#entretienDetailModal').modal('hide');
                                $('#calendar').fullCalendar('refetchEvents');
                                Swal.fire('Annulé!', 'L\'entretien a été annulé.', 'success');
                            }
                        });
                    }
                });
            });

            // Ouvrir le modal
            $('#entretienDetailModal').modal('show');
        },
        error: function() {
            Swal.fire('Erreur', 'Impossible de charger les détails.', 'error');
        }
    });

                }
            });
        });
    </script>
@endpush
