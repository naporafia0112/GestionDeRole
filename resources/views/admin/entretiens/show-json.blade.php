<div class="card d-block">
    <div class="card-body position-relative">
        <!-- Menu dropdown flottant à droite -->
        <div class="dropdown float-end">
            <a href="#" class="dropdown-toggle arrow-none text-muted" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-dots-horizontal font-18"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="{{ route('entretiens.edit', $entretien->id) }}" class="dropdown-item">
                    <i class="mdi mdi-pencil-outline me-1"></i> Modifier
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" id="btn-delete-entretien" class="dropdown-item text-danger">
                    <i class="mdi mdi-delete-outline me-1"></i> Supprimer
                </a>
            </div>
        </div>

        <!-- Checkbox de statut avec style amélioré -->
        <div class="form-check form-switch float-start mb-3">
            <input type="checkbox" class="form-check-input" id="completedCheck" {{ $entretien->statut === 'termine' ? 'checked' : '' }} />
            <label class="form-check-label fw-medium" for="completedCheck">
                {{ $entretien->statut === 'termine' ? 'Terminé' : 'Marquer comme terminé' }}
            </label>
        </div>
        <div class="clearfix"></div>

        <!-- Titre principal avec badge de statut -->
        <div class="d-flex align-items-center mb-4">
            <h4 class="mb-0 me-3">Entretien {{ ucfirst($entretien->type) }}</h4>
            <span class="badge bg-{{ $entretien->statut === 'termine' ? 'success' : ($entretien->statut === 'annule' ? 'danger' : 'warning') }} align-middle">
                {{ ucfirst($entretien->statut) }}
            </span>
        </div>

        <!-- Section d'informations principales -->
        <div class="row g-3 mb-4">
            <!-- Candidat -->
            <div class="col-md-4">
                <div class="border rounded p-3 h-100">
                    <h6 class="text-muted text-uppercase small mb-3">Candidat</h6>
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/images/users/user-default.jpg') }}" alt="{{ $entretien->candidat->nom }}"
                             class="rounded-circle me-3" width="48" height="48" />
                        <div>
                            <h5 class="mb-1">{{ $entretien->candidat->nom }} {{ $entretien->candidat->prenoms }}</h5>
                            <span class="text-muted small">{{ $entretien->candidat->email ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Offre -->
            <div class="col-md-4">
                <div class="border rounded p-3 h-100">
                    <h6 class="text-muted text-uppercase small mb-3">Offre</h6>
                    <div class="d-flex align-items-center">
                        <div class="bg-soft-primary rounded p-2 me-3">
                            <i class="mdi mdi-briefcase-check-outline font-18 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $entretien->offre->titre ?? 'Non renseigné' }}</h5>
                            <span class="text-muted small">Réf: {{ $entretien->offre->reference ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date & Heure -->
            <div class="col-md-4">
                <div class="border rounded p-3 h-100">
                    <h6 class="text-muted text-uppercase small mb-3">Date & Heure</h6>
                    <div class="d-flex align-items-center">
                        <div class="bg-soft-success rounded p-2 me-3">
                            <i class="mdi mdi-calendar-month-outline font-18 text-success"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ \Carbon\Carbon::parse($entretien->date)->format('d/m/Y') }}</h5>
                            <span class="text-muted small">À {{ $entretien->heure }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Détails et Commentaire -->
        <div class="row g-3">
            <!-- Détails -->
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-information-outline text-primary me-2"></i> Détails de l'entretien
                        </h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Type</span>
                                <span class="fw-medium">{{ ucfirst($entretien->type) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Lieu</span>
                                <span class="fw-medium">{{ $entretien->lieu }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Durée</span>
                                <span class="fw-medium">{{ $entretien->duree }} minutes</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Commentaire -->
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-comment-text-outline text-primary me-2"></i> Commentaire
                        </h5>
                        <div class="bg-light p-3 rounded">
                            @if($entretien->commentaire)
                                {{ $entretien->commentaire }}
                            @else
                                <span class="text-muted fst-italic">Aucun commentaire renseigné</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex justify-content-end mt-4 gap-2">
            @if($entretien->statut !== 'annule')
                <button id="btn-cancel-entretien" class="btn btn-outline-danger">
                    <i class="mdi mdi-cancel me-1"></i> Annuler l'entretien
                </button>
            @endif
            <a href="{{ route('entretiens.edit', $entretien->id) }}" class="btn btn-primary">
                <i class="mdi mdi-pencil-outline me-1"></i> Modifier
            </a>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Suppression avec SweetAlert
    $('#btn-delete-entretien').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Confirmer la suppression',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('entretiens.destroy', $entretien->id) }}",
                    method: 'DELETE',
                    data: {_token: "{{ csrf_token() }}"},
                    success: function() {
                        $('#entretienDetailModal').modal('hide');
                        $('#calendar').fullCalendar('refetchEvents');
                        Swal.fire({
                            title: 'Supprimé!',
                            text: 'L\'entretien a été supprimé.',
                            icon: 'success',
                            timer: 1500
                        });
                    },
                    error: function() {
                        Swal.fire('Erreur!', 'Une erreur est survenue.', 'error');
                    }
                });
            }
        });
    });

    // Changement de statut (terminé/prévu)
    $('#completedCheck').change(function() {
        const newStatus = this.checked ? 'termine' : 'prevu';
        const action = this.checked ? 'terminé' : 'en attente';

        $.ajax({
            url: "{{ route('entretiens.update-status', $entretien->id) }}",
            method: 'PATCH',
            data: {
                statut: newStatus,
                _token: "{{ csrf_token() }}"
            },
            success: function() {
                $('#entretienDetailModal').modal('hide');
                $('#calendar').fullCalendar('refetchEvents');
                Swal.fire({
                    title: 'Succès!',
                    text: 'L\'entretien a été marqué comme ' + action + '.',
                    icon: 'success',
                    timer: 1500
                });
            },
            error: function() {
                Swal.fire('Erreur!', 'Une erreur est survenue.', 'error');
                $('#completedCheck').prop('checked', !this.checked);
            }
        });
    });

    // Annulation de l'entretien
    $('#btn-cancel-entretien').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Annuler cet entretien?',
            text: "Vous pourrez le réactiver ultérieurement.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, annuler',
            cancelButtonText: 'Non'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('entretiens.annuler', $entretien->id) }}",
                    method: 'PATCH',
                    data: {_token: "{{ csrf_token() }}"},
                    success: function() {
                        $('#entretienDetailModal').modal('hide');
                        $('#calendar').fullCalendar('refetchEvents');
                        Swal.fire({
                            title: 'Annulé!',
                            text: 'L\'entretien a été annulé.',
                            icon: 'success',
                            timer: 1500
                        });
                    },
                    error: function() {
                        Swal.fire('Erreur!', 'Une erreur est survenue.', 'error');
                    }
                });
            }
        });
    });
});
</script>
