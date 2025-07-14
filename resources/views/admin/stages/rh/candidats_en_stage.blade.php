@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4">Candidats actuellement en stage</h4>

            @if ($candidats->isEmpty())
                <div class="alert alert-info">Aucun candidat en stage actuellement.</div>
            @else
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="min-date">Date début</label>
                    <input type="date" id="min-date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="max-date">Date fin</label>
                    <input type="date" id="max-date" class="form-control">
                </div>
            </div>

                <div class="table-responsive">
                    <table id="candidats-datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date début stage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($candidats as $candidat)
                                <tr>
                                    <td>{{ $candidat->nom }}</td>
                                    <td>{{ $candidat->prenoms ?? $candidat->prenom ?? '' }}</td>
                                    <td>{{ $candidat->email ?? '---' }}</td>
                                    <td>{{ $candidat->telephone ?? '---' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($candidat->date_debut)->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$('#candidats-datatable').DataTable({
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
                modifier: {
                    search: 'applied'
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
$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    let min = $('#min-date').val();
    let max = $('#max-date').val();
    let date = data[4];

    if (!min && !max) return true;

    let dateObj = new Date(date);

    if (
        (!min || new Date(min) <= dateObj) &&
        (!max || new Date(max) >= dateObj)
    ) {
        return true;
    }
    return false;
});

$('#min-date, #max-date').on('change', function () {
    $('#candidats-datatable').DataTable().draw();
});


</script>
@endpush

@push('styles')
<!-- DataTables css -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush
