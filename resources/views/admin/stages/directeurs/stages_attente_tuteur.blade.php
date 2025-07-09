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
                                <li class="breadcrumb-item active">Stages en attente</li>
                            </ol>
                        </div>
                        <h4 class="page-title"><strong>Liste des stages en attente de tuteurs</strong></h4>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="stages-datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Candidat</th>
                                <th>Tuteur</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Sujet</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stages as $stage)
                                <tr>
                                    <td>{{ $stage->candidature->candidat->nom ?? '' }} {{ $stage->candidature->candidat->prenoms ?? '' }}</td>
                                    <td>{{ $stage->tuteur->name ?? '----------' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') }}</td>
                                    <td>{{ $stage->sujet }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $stage->statut)) }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('stages.show', $stage->id) }}" class="btn btn-sm btn-info" title="Détails">
                                                <i class="fe-eye"></i>
                                            </a>
                                            <!-- Bouton pour ouvrir modal affectation tuteur -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-success"
                                                title="Affecter un tuteur"
                                                data-bs-toggle="modal"
                                                data-bs-target="#affecterTuteurModal"
                                                data-stage-id="{{ $stage->id }}"
                                                data-candidat-nom="{{  $stage->candidature->candidat->nom }}"
                                            >
                                                <i class="fe-plus-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Aucun stage trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>

<!-- Modal Affecter un tuteur -->
<div class="modal fade" id="affecterTuteurModal" tabindex="-1" aria-labelledby="affecterTuteurModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="affectationForm" method="POST" action="">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="affecterTuteurModalLabel">Affecter un tuteur au stage</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="candidatNom" class="form-label">Candidat :</label>
            <input type="text" id="candidatNom" class="form-control" disabled>
          </div>
          <div class="mb-3">
            <label for="id_tuteur" class="form-label">Tuteur à affecter <span class="text-danger">*</span></label>
            <select name="id_tuteur" id="id_tuteur" class="form-select" >
              <option value="">-- Sélectionner un tuteur --</option>
              @foreach ($tuteurs as $tuteur)
                <option value="{{ $tuteur->id }}">{{ $tuteur->name }} ({{ $tuteur->email }})</option>
              @endforeach
            </select>
            <div class="invalid-feedback">Veuillez sélectionner un tuteur.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-success"><i class="fe-check-circle"></i> Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />

<style>
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 4px;
        padding: 5px 10px;
        border: 1px solid #dee2e6;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 4px;
        padding: 5px;
        border: 1px solid #dee2e6;
    }
    .table > :not(:first-child) {
        border-top: none;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .d-flex.gap-1 {
        gap: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#stages-datatable')) {
        $('#stages-datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            columnDefs: [{ orderable: false, targets: 6 }],
            order: [[2, 'desc']],
            responsive: true,
            pageLength: 10,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        });
    }

    $('#affecterTuteurModal').on('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const stageId = $(button).data('stage-id');
        const nom = $(button).data('candidat-nom');

        $('#candidatNom').val(nom);
        $('#affectationForm').attr('action', '/stages/' + stageId + '/affecter-tuteur');
        $('#affectationForm')[0].reset();
        $('#affectationForm').removeClass('was-validated');
    });

    $('#affectationForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;

        if (form.checkValidity()) {
            Swal.fire({
                title: 'Confirmer l\'affectation ?',
                text: "Voulez-vous vraiment affecter ce tuteur au stage ?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, enregistrer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            form.classList.add('was-validated');
        }
    });
});
</script>
@endpush
