@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                    <li class="breadcrumb-item active">Liste des entretiens</li>
                                </ol>
                            </nav>
                        </div>
                        <h4 class="mb-4" style="font-size: 25px">Liste de tous les entretiens</h4>
                    </div>
                </div>
            </div>

            <!-- FILTRE STATUT -->
            <div class="mb-3">
                <label for="filterStatut" class="form-label"><strong>Filtrer par statut :</strong></label>
                <select id="filterStatut" class="form-select" style="max-width: 300px;">
                    <option value="" selected>Tous</option>
                    <option value="prevu">Prévu</option>
                    <option value="annule">Annulé</option>
                    <option value="encours">En cours</option>
                    <option value="effectuee">Effectuée</option>
                    <option value="termine">Terminé</option>
                </select>
                <span class="badge bg-secondary ms-2" id="count-statut"></span>
            </div>

            <!-- FILTRE TYPE -->
            <div class="mb-3">
                <label for="filterType" class="form-label"><strong>Filtrer par type de candidature :</strong></label>
                <select id="filterType" class="form-select" style="max-width: 300px;">
                    <option value="" selected>Tous</option>
                    <option value="offre">Offre</option>
                    <option value="spontanee">Spontanée</option>
                </select>
                <span class="badge bg-secondary ms-2" id="count-type"></span>
            </div>

            <div class="table-responsive">
                <table id="entretiens-table" class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Candidat</th>
                            <th>Offre</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entretiens as $entretien)
                            <tr data-statut="{{ strtolower($entretien->statut) }}" data-type="{{ $entretien->offre ? 'offre' : 'spontanee' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $entretien->candidat->nom ?? '-' }} {{ $entretien->candidat->prenoms ?? '' }}</td>
                                <td>{{ $entretien->offre->titre ?? 'Entretien spontané' }}</td>
                                <td>{{ \Carbon\Carbon::parse($entretien->date)->format('d/m/Y') }}</td>
                                <td>{{ $entretien->heure ? \Carbon\Carbon::parse($entretien->heure)->format('H:i') : '--' }}</td>
                                <td>
                                    <span class="badge bg-{{ $entretien->statut === 'annule' ? 'danger' : ($entretien->statut === 'effectuee' ? 'success' : 'warning') }}">
                                        {{ ucfirst($entretien->statut) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {
    const table = $('#entretiens-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pdfHtml5',
                className: 'btn btn-sm btn-danger',
                text: '<i class="mdi mdi-file-pdf"></i> Exporter en PDF',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    },
                    columns: ':visible'
                },
                customize: function (doc) {
                    doc.styles.title = {
                        alignment: 'center',
                        fontSize: 16,
                        bold: true,
                        margin: [0, 0, 0, 12]
                    };

                    doc.styles.tableHeader = {
                        alignment: 'center',
                        bold: true,
                        fontSize: 12
                    };

                    const contentTable = doc.content.find(item => item.table);
                    if (contentTable) {
                        // Centrer le texte de chaque cellule
                        contentTable.table.body.forEach(row => {
                            row.forEach(cell => {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center';
                                }
                            });
                        });

                        // Méthode simple et SÛRE pour centrer le tableau : alignement global
                        contentTable.alignment = 'center';
                    }
                }

            },
            {
                extend: 'excelHtml5',
                className: 'btn btn-sm btn-success',
                text: '<i class="mdi mdi-file-excel"></i> Exporter en Excel',
                exportOptions: {
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    },
                    columns: ':visible'
                }
            }
        ]
    });

    function filtrerTable() {
        const statut = $('#filterStatut').val();
        const type = $('#filterType').val();

        table.rows().every(function () {
            const $row = $(this.node());
            const rowStatut = $row.data('statut');
            const rowType = $row.data('type');

            const matchStatut = !statut || rowStatut === statut;
            const matchType = !type || rowType === type;

            $row.toggle(matchStatut && matchType);
        });
    }

    $('#filterStatut, #filterType').on('change', function () {
        filtrerTable();
    });
});
</script>
@endpush
