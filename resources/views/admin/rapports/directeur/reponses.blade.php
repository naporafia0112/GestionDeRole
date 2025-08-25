@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.directeur') }}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('directeur.formulaires.liste') }}">Liste des formulaires de rapports</a></li>
                                <li class="breadcrumb-item active">Raports</li>
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <strong>{{ $formulaire->titre }}</strong>
                        </h4>
                    </div>
                </div>
                <div class="col-auto">
                <a href="{{ route('directeur.formulaires.liste') }}" class="btn btn-sm btn-link"><i class="mdi mdi-keyboard-backspace"></i>Retour</a>
                </div>
            </div>
                @if ($formulaire->reponses->isEmpty())
                    <div class="alert alert-info">Aucune réponse encore reçue.</div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tuteur</th>
                                            <th>Candidat</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($formulaire->reponses as $reponse)
                                            <tr>
                                                <td>{{ $reponse->tuteur->name }}</td>
                                                <td>{{ $reponse->stage->candidature->candidat->nom ?? 'N/A' }}</td>
                                                <td>{{ $reponse->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <!-- Bouton d'ouverture du modal -->
                                                    <button class="btn btn-sm btn-info" title="Voir" data-bs-toggle="modal" data-bs-target="#modal-reponse-{{ $reponse->id }}">
                                                        <i class="fe-eye"></i>
                                                    </button>
                                                    <button
                                                        class="btn btn-sm {{ $reponse->valide ? 'btn-danger' : 'btn-success' }} btn-valider"
                                                        data-reponse-id="{{ $reponse->id }}"
                                                        title="{{ $reponse->valide ? 'Validation faite' : 'Valider' }}"
                                                        {{ $reponse->valide ? 'disabled' : '' }}>
                                                        @if($reponse->valide)
                                                            <i class="fe-check-circle"></i> Validation faite
                                                        @else
                                                            <i class="fe-check"></i> OK
                                                        @endif
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modal-reponse-{{ $reponse->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $reponse->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel{{ $reponse->id }}">
                                                                Réponse de {{ $reponse->tuteur->name }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <ul class="list-group">
                                                                @foreach ($reponse->champs as $champ)
                                                                    <li class="list-group-item">
                                                                        <strong>{{ $champ->champFormulaire->label }} :</strong><br>
                                                                        {{ $champ->valeur }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- table-responsive -->
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                @endif
            </div> <!-- row -->
        </div> <!-- card-body -->
    </div> <!-- card -->
</div>
@endsection

@push('scripts')
<!-- DataTables & Bootstrap -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            }
        });

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        $('.btn-valider').on('click', function () {
            const btn = $(this);
            if(btn.prop('disabled')) return;  // ne rien faire si déjà désactivé

            const reponseId = btn.data('reponse-id');

            Swal.fire({
                title: 'Valider le stage ?',
                text: "Cette action confirmera la validation du stage lié à cette réponse.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, valider',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.prop('disabled', true).html('Validation...');

                    fetch(`/reponses-formulaire/${reponseId}/valider`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Validé !',
                                text: 'La validation a été enregistrée.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            btn
                                .removeClass('btn-success')
                                .addClass('btn-danger')
                                .prop('disabled', true)
                                .html('<i class="fe-check-circle"></i> Validation faite');
                        } else {
                            Swal.fire('Erreur', data.message || 'Erreur lors de la validation', 'error');
                            btn.prop('disabled', false).html('<i class="fe-check"></i> OK');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Erreur', 'Une erreur réseau est survenue', 'error');
                        btn.prop('disabled', false).html('<i class="fe-check"></i> OK');
                    });
                }
            });
        });

    });
</script>
@endpush
