@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Liste des entretiens</h4>
                <select id="filtre-statut" class="form-select w-auto">
                    <option value="">Tous les statuts</option>
                    <option value="prevu">Prévu</option>
                    <option value="annule">Annulé</option>
                    <option value="encours">En cours</option>
                    <option value="effectuee">Effectuée</option>
                    <option value="termine">Terminé</option>
                </select>
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
                            <tr data-statut="{{ strtolower($entretien->statut) }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $entretien->candidat->nom ?? '-' }} {{ $entretien->candidat->prenoms ?? '' }}</td>
                                <td>{{ $entretien->offre->titre ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($entretien->date)->format('d/m/Y') }}</td>
                                <td>{{ $entretien->heure ?? '--' }}</td>
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
    var table = $('#entretiens-table').DataTable({
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
    },
    responsive: true,
    pageLength: 10,
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'pdfHtml5',
            className: 'btn btn-sm btn-danger',
            text: '<i class="mdi mdi-file-pdf"></i> Exporter en PDF',
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: {
                rows: function (idx, data, node) {
                    // Récupérer le filtre sélectionné
                    let selectedStatut = $('#filtre-statut').val();
                    if (!selectedStatut) return true; // tous les statuts
                    let statutRow = $(node).data('statut'); // attribut data-statut dans la ligne <tr>
                    return statutRow === selectedStatut;
                }
            },
            customize: function (doc) {
                // Centrer le titre
                doc.styles.title = {
                    alignment: 'center',
                    fontSize: 14
                };

                // Centrer les en-têtes de colonne
                doc.styles.tableHeader = {
                    alignment: 'center',
                    bold: true,
                    fontSize: 12
                };

                // Parcourir tous les éléments du document
                doc.content.forEach(function (contentItem) {
                    if (contentItem.table && contentItem.table.body) {
                        contentItem.table.body.forEach(function (row) {
                            row.forEach(function (cell) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center';
                                }
                            });
                        });
                    }
                });
            }
        }
    ]
});

// Filtrer dynamiquement le tableau à l'écran
$('#filtre-statut').on('change', function () {
    let statut = $(this).val().toLowerCase();
    if (statut) {
        table.rows().every(function () {
            let rowStatut = $(this.node()).data('statut');
            $(this.node()).toggle(rowStatut === statut);
        });
    } else {
        table.rows().every(function () {
            $(this.node()).show();
        });
    }
});
</script>
@endpush
