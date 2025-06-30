<div class="card d-block">
    <div class="card-body">
        <div class="dropdown float-end">
            <a href="#" class="dropdown-toggle arrow-none text-muted" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-dots-horizontal font-18"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="{{ route('entretiens.edit', $entretien->id) }}" class="dropdown-item">
                    <i class="mdi mdi-pencil-outline me-1"></i>Modifier
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" id="btn-delete-entretien" class="dropdown-item text-danger">
                    <i class="mdi mdi-delete-outline me-1"></i>Supprimer
                </a>
            </div>
        </div>

        <div class="form-check float-start">
            <input type="checkbox" class="form-check-input" id="completedCheck" {{ $entretien->statut === 'termine' ? 'checked' : '' }} />
            <label class="form-check-label" for="completedCheck">
                {{ $entretien->statut === 'termine' ? 'Terminé' : 'Marquer comme terminé' }}
            </label>
        </div>
        <div class="clearfix"></div>

        <h4>Entretien {{ ucfirst($entretien->type) }} - {{ $entretien->candidat->nom }} {{ $entretien->candidat->prenoms }}</h4>

        <div class="row">
            <div class="col-md-4">
                <p class="mt-2 mb-1 text-muted">Candidat</p>
                <div class="d-flex align-items-start">
                    <img src="{{ asset('assets/images/users/user-default.jpg') }}" alt="{{ $entretien->candidat->nom }}" class="rounded-circle me-2" height="24" />
                    <div class="w-100">
                        <h5 class="mt-1 font-size-14">
                            {{ $entretien->candidat->nom }} {{ $entretien->candidat->prenoms }}
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <p class="mt-2 mb-1 text-muted">Offre</p>
                <div class="d-flex align-items-start">
                    <i class="mdi mdi-briefcase-check-outline font-18 text-success me-1"></i>
                    <div class="w-100">
                        <h5 class="mt-1 font-size-14">
                            {{ $entretien->offre->titre ?? 'Non renseigné' }}
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <p class="mt-2 mb-1 text-muted">Date & Heure</p>
                <div class="d-flex align-items-start">
                    <i class="mdi mdi-calendar-month-outline font-18 text-success me-1"></i>
                    <div class="w-100">
                        <h5 class="mt-1 font-size-14">
                            {{ \Carbon\Carbon::parse($entretien->date)->format('d/m/Y') }} à {{ $entretien->heure }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <h5 class="mt-3">Détails :</h5>
                <ul class="list-unstyled text-muted">
                    <li><strong>Lieu :</strong> {{ $entretien->lieu }}</li>
                    <li><strong>Statut :</strong> 
                        <span class="badge bg-{{ $entretien->statut === 'termine' ? 'success' : ($entretien->statut === 'annule' ? 'danger' : 'warning') }}">
                            {{ ucfirst($entretien->statut) }}
                        </span>
                    </li>
                    <li><strong>Type :</strong> {{ ucfirst($entretien->type) }}</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5 class="mt-3">Commentaire :</h5>
                <div class="card bg-light p-2">
                    {{ $entretien->commentaire ?? 'Aucun commentaire' }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // Supprimer
    $('#btn-delete-entretien').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Confirmer la suppression',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('entretiens.destroy', $entretien->id) }}",
                    method: 'DELETE',
                    data: {_token: "{{ csrf_token() }}"},
                    success: function() {
                        $('#entretienDetailModal').modal('hide');
                        $('#calendar').fullCalendar('refetchEvents'); // rafraîchit le calendrier
                        Swal.fire('Supprimé!', 'Entretien supprimé.', 'success');
                    }
                });
            }
        });
    });

    // Terminer (checkbox)
    $('#completedCheck').change(function() {
        const newStatus = this.checked ? 'termine' : 'prevu';
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
                Swal.fire('Succès', 'Statut mis à jour.', 'success');
            }
        });
    });

    // Annuler
    $('#btn-cancel-entretien').click(function() {
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
                    url: "{{ route('entretiens.annuler', $entretien->id) }}",
                    method: 'PATCH',
                    data: {_token: "{{ csrf_token() }}"},
                    success: function() {
                        $('#entretienDetailModal').modal('hide');
                        $('#calendar').fullCalendar('refetchEvents');
                        Swal.fire('Succès', 'Entretien annulé.', 'success');
                    }
                });
            }
        });
    });
});
</script>
